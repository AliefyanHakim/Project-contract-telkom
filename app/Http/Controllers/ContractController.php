<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contract;
use App\Models\User;
use App\Models\Service;
use App\Models\ContractService;
use App\Models\ContractFile;
use App\Models\BasoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Billing;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContractController extends Controller
{

private function ensureContractAccess(Contract $contract): void
{
    $user = Auth::user();

    if ($user->isAccountManager() && $contract->owner_am_id !== $user->id) {
        abort(403, 'Anda tidak memiliki akses ke kontrak ini.');
    }
}

private function generateBillingsForContract(Contract $contract): void
{
    $contract->loadMissing('services.service');

    $monthlyAmount = $contract->services->sum(function ($contractService) {
        return (float) (
            $contractService->monthly_fee
            ?? $contractService->service?->monthly_fee
            ?? 0
        );
    });

    if ($monthlyAmount <= 0) {
        return;
    }

    $start = Carbon::parse($contract->start_date)->startOfMonth();
    $end = Carbon::parse($contract->end_date)->startOfMonth();

    while ($start->lte($end)) {
        Billing::firstOrCreate(
            [
                'contract_id' => $contract->id,
                'billing_period' => $start->format('Y-m'),
            ],
            [
                'due_date' => $start->copy()->day(20)->toDateString(),
                'amount' => $monthlyAmount,
                'payment_status' => 'pending',
            ]
        );

        $start->addMonth();
    }
}
    /**
     * Display a listing of contracts.
     */
    public function index(Request $request)
    {
        $query = Contract::with([
            'owner',
            'creator',
            'services.service'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | AM hanya melihat kontrak miliknya
        |--------------------------------------------------------------------------
        */
        if ($user->isAccountManager()) {

            $query->where(
                'owner_am_id',
                $user->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Current Contract
        |--------------------------------------------------------------------------
        */
        $query->whereNotIn('status', [
            'expired',
            'terminated'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'contract_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'contract_number',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter AM
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('account_manager')
            && !$user->isAccountManager()
        ) {

            $query->where(
                'owner_am_id',
                $request->account_manager
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Service
        |--------------------------------------------------------------------------
        */
        if ($request->filled('service')) {

            $query->whereHas(
                'services',
                function ($q) use ($request) {

                    $q->where(
                        'service_id',
                        $request->service
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $contracts = $query
            ->orderBy('end_date')
            ->paginate(15)
            ->withQueryString();

        $accountManagers = User::where(
            'role_id',
            User::ROLE_ACCOUNT_MANAGER
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        $services = Service::where(
            'status',
            'active'
        )
        ->orderBy('service_name')
        ->get();

        return view(
            'contracts.contract-list',
            compact(
                'contracts',
                'accountManagers',
                'services'
            )
        );
    }

public function closedContracts(Request $request)
{
    $query = Contract::with([
        'owner',
        'creator',
        'services.service',
    ])
    ->whereIn('status', [
        'expired',
        'terminated',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->isAccountManager()) {
        $query->where('owner_am_id', $user->id);
    }

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('contract_name', 'like', "%{$search}%")
                ->orWhere('contract_number', 'like', "%{$search}%")
                ->orWhere('account_number', 'like', "%{$search}%")
                ->orWhere('sid', 'like', "%{$search}%")
                ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                    $ownerQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    if (
        $request->filled('account_manager')
        && !$user->isAccountManager()
    ) {
        $query->where('owner_am_id', $request->account_manager);
    }

    if ($request->filled('service')) {
        $query->whereHas('services', function ($q) use ($request) {
            $q->where('service_id', $request->service);
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $contracts = $query
        ->orderByDesc('end_date')
        ->paginate(15)
        ->withQueryString();

    $accountManagers = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    $services = Service::where('status', 'active')
        ->orderBy('service_name')
        ->get();

    return view(
        'contracts.closed-contract',
        compact(
            'contracts',
            'accountManagers',
            'services'
        )
    );
}

    public function getCalculatedStatusAttribute()
    {
        if ($this->status === 'terminated') {
            return 'terminated';
        }

        if ($this->status === 'expired') {
            return 'expired';
        }

        $daysRemaining = now()->diffInDays(
            $this->end_date,
            false
        );

        if ($daysRemaining < 0) {
            return 'expired';
        }

        if ($daysRemaining <= 7) {
            return 'followup';
        }

        if ($daysRemaining <= 30) {
            return 'expiring';
        }

        return 'active';
    }

    public function viewFile(ContractFile $file)
{
    $this->ensureContractAccess($file->contract);

    if (!Storage::exists($file->file_path)) {
        abort(404, 'File not found');
    }

    ActivityLogger::log(
        'FILE',
        'Viewed file ' . $file->file_name
    );

    return response()->file(
        Storage::path($file->file_path),
        [
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"',
        ]
    );
}

    /**
     * Show form create contract.
     */
    public function create()
    {
        $accountManagers = User::where(
            'role_id',
            User::ROLE_ACCOUNT_MANAGER
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        $services = Service::where(
            'status',
            'active'
        )
        ->orderBy('service_name')
        ->get();

        return view(
            'contracts.add-contract',
            compact(
                'accountManagers',
                'services'
            )
        );
    }
    /**
     * Store contract.
     */
public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'contract_number' => [
            'required',
            'max:100',
            'unique:contracts,contract_number',
        ],

        'contract_name' => [
            'required',
            'max:255',
        ],

        'account_number' => [
            'nullable',
            'max:100',
        ],

        'sid' => [
            'nullable',
            'max:100',
        ],

        'telkom_name' => [
            'nullable',
            'max:255',
        ],

        'telkom_position' => [
            'nullable',
            'max:255',
        ],

        'telkom_unit' => [
            'nullable',
            'max:255',
        ],

        'customer_address' => [
            'nullable',
        ],

        'customer_npwp' => [
            'nullable',
            'max:100',
        ],

        'customer_pic_name' => [
            'nullable',
            'max:255',
        ],

        'customer_pic_position' => [
            'nullable',
            'max:255',
        ],

        'customer_phone' => [
            'nullable',
            'max:50',
        ],

        'customer_email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'owner_am_id' => [
            'nullable',
            'exists:users,id',
        ],

        'start_date' => [
            'required',
            'date',
        ],

        'end_date' => [
            'required',
            'date',
            'after:start_date',
        ],

        'signing_date' => [
            'nullable',
            'date',
        ],

        'signing_location' => [
            'nullable',
            'max:255',
        ],

        'services' => [
            'required',
            'array',
            'min:1',
        ],

        'services.*' => [
            'required',
            'exists:services,id',
        ],

        'file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
            'max:10240',
        ],

        'contract_file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
            'max:10240',
        ],

        'baso_files.*' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
        ],

        'baso_dates.*' => [
            'nullable',
            'date',
        ],

        'custom_service_name' => [
            'nullable',
            'max:255',
        ],

        'custom_installation_fee' => [
            'nullable',
            'numeric',
        ],

        'custom_monthly_fee' => [
            'nullable',
            'numeric',
        ],
    ]);

    if ($user->isAccountManager()) {
        $ownerAmId = $user->id;
    } else {
        $ownerAmId = $validated['owner_am_id'] ?? null;
    }

    if (!$ownerAmId) {
        return back()
            ->withInput()
            ->withErrors([
                'owner_am_id' => 'Account Manager wajib dipilih.',
            ]);
    }

    $contract = null;

    DB::transaction(function () use ($validated, $request, &$contract, $ownerAmId) {
        $contract = Contract::create([
            'contract_number' => $validated['contract_number'],
            'contract_name' => $validated['contract_name'],
            'account_number' => $validated['account_number'] ?? null,
            'sid' => $validated['sid'] ?? null,

            'telkom_name' => $validated['telkom_name'] ?? null,
            'telkom_position' => $validated['telkom_position'] ?? null,
            'telkom_unit' => $validated['telkom_unit'] ?? null,

            'customer_address' => $validated['customer_address'] ?? null,
            'customer_npwp' => $validated['customer_npwp'] ?? null,
            'customer_pic_name' => $validated['customer_pic_name'] ?? null,
            'customer_pic_position' => $validated['customer_pic_position'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,

            'signing_date' => $validated['signing_date'] ?? null,
            'signing_location' => $validated['signing_location'] ?? null,

            'owner_am_id' => $ownerAmId,

            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],

            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        if ($request->filled('custom_service_name')) {
            $newService = Service::create([
                'service_name' => $request->custom_service_name,
                'installation_fee' => $request->custom_installation_fee ?? 0,
                'monthly_fee' => $request->custom_monthly_fee ?? 0,
                'status' => 'active',
            ]);

            $validated['services'][] = $newService->id;
        }

        foreach ($validated['services'] as $serviceId) {
            $service = Service::findOrFail($serviceId);

            ContractService::create([
                'contract_id' => $contract->id,
                'service_id' => $service->id,
                'installation_fee' => $service->installation_fee ?? 0,
                'monthly_fee' => $service->monthly_fee ?? 0,
            ]);
        }

        $this->generateBillingsForContract($contract);

        $contractFileInput = null;

        if ($request->hasFile('file')) {
            $contractFileInput = 'file';
        } elseif ($request->hasFile('contract_file')) {
            $contractFileInput = 'contract_file';
        }

        if ($contractFileInput) {
            $file = $request->file($contractFileInput);
            $path = $file->store('contracts');

            ContractFile::create([
                'contract_id' => $contract->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

        if ($request->hasFile('baso_files')) {
            foreach ($request->file('baso_files') as $index => $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('baso');

                BasoFile::create([
                    'contract_id' => $contract->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'baso_date' => $request->baso_dates[$index] ?? null,
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        $contract->refresh();
        $contract->updateStatus();
    });

    ActivityLogger::log(
        'CONTRACT',
        'Created contract ' . $contract->contract_number
    );

    return redirect()
        ->route('contracts.show', $contract->id)
        ->with('success', 'Contract created successfully.');
}

    /**
     * Show contract detail.
     */
    public function show(Contract $contract)
{
    $this->ensureContractAccess($contract);

    $contract->load([
    'owner',
    'services.service',
    'files',
    'basoFiles',
]);

    return view(
        'contracts.detail-contract',
        compact('contract')
    );
    }

    public function edit(Contract $contract)
    {
    $this->ensureContractAccess($contract);

    $contract->load([
    'owner',
    'services.service',
    'files',
    'basoFiles',
]);

    $accountManagers = User::where(
        'role_id',
        User::ROLE_ACCOUNT_MANAGER
    )
    ->where('status', 'active')
    ->orderBy('name')
    ->get();

    $services = Service::orderBy(
        'service_name'
    )->get();

    return view(
        'contracts.edit-contract',
        compact(
            'contract',
            'accountManagers',
            'services'
        )
    );
    }

    public function update(Request $request, Contract $contract)
{
    $this->ensureContractAccess($contract);

    $user = Auth::user();

    if ($user->isSupportPaycall()) {
        $validated = $request->validate([
            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
        ]);

        

        $contract->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        $contract->refresh();
        $contract->updateStatus();

        ActivityLogger::log(
            'CONTRACT',
            'Updated contract date/status ' . $contract->contract_number
        );

        return redirect()
            ->route('contracts.show', $contract->id)
            ->with('success', 'Start date dan end date kontrak berhasil diperbarui.');
    }

    $validated = $request->validate([
        'file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
            'max:10240',
        ],

        'contract_file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
            'max:10240',
        ],

        'contract_name' => [
            'required',
            'max:255',
        ],

        'account_number' => [
            'nullable',
            'max:100',
        ],

        'sid' => [
            'nullable',
            'max:100',
        ],

        'customer_address' => [
            'nullable',
        ],

        'customer_npwp' => [
            'nullable',
            'max:100',
        ],

        'customer_pic_name' => [
            'nullable',
            'max:255',
        ],

        'customer_pic_position' => [
            'nullable',
            'max:255',
        ],

        'customer_phone' => [
            'nullable',
            'max:50',
        ],

        'customer_email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'owner_am_id' => [
            'nullable',
            'exists:users,id',
        ],

        'start_date' => [
            'required',
            'date',
        ],

        'end_date' => [
            'required',
            'date',
            'after:start_date',
        ],

        'services' => [
            'required',
            'array',
            'min:1',
        ],

        'services.*' => [
            'required',
            'exists:services,id',
        ],

        'custom_services' => [
            'nullable',
            'array',
        ],

        'baso_files.*' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx',
        ],

        'baso_dates.*' => [
            'nullable',
            'date',
        ],
    ]);

    $ownerAmId = $contract->owner_am_id;

    if ($user->isAccountManager()) {
        $ownerAmId = $user->id;
    } elseif ($request->filled('owner_am_id')) {
        $ownerAmId = $request->owner_am_id;
    }

    DB::transaction(function () use ($validated, $request, $contract, $ownerAmId) {
        $contract->update([
            'contract_name' => $validated['contract_name'],
            'account_number' => $validated['account_number'] ?? null,
            'sid' => $validated['sid'] ?? null,

            'customer_address' => $validated['customer_address'] ?? null,
            'customer_npwp' => $validated['customer_npwp'] ?? null,
            'customer_pic_name' => $validated['customer_pic_name'] ?? null,
            'customer_pic_position' => $validated['customer_pic_position'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,

            'owner_am_id' => $ownerAmId,

            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        ContractService::where('contract_id', $contract->id)->delete();

        foreach ($validated['services'] as $serviceId) {
            $service = Service::findOrFail($serviceId);

            ContractService::create([
                'contract_id' => $contract->id,
                'service_id' => $service->id,
                'installation_fee' => $service->installation_fee ?? 0,
                'monthly_fee' => $service->monthly_fee ?? 0,
            ]);
        }

        $this->generateBillingsForContract($contract);

        foreach ($request->custom_services ?? [] as $customService) {
            if (empty($customService['service_name'])) {
                continue;
            }

            $service = Service::create([
                'service_name' => $customService['service_name'],
                'installation_fee' => $customService['installation_fee'] ?? 0,
                'monthly_fee' => $customService['monthly_fee'] ?? 0,
                'status' => 'active',
            ]);

            ContractService::create([
                'contract_id' => $contract->id,
                'service_id' => $service->id,
                'installation_fee' => $service->installation_fee ?? 0,
                'monthly_fee' => $service->monthly_fee ?? 0,
            ]);
        }

        if ($request->hasFile('baso_files')) {
            foreach ($request->file('baso_files') as $index => $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('baso');

                BasoFile::create([
                    'contract_id' => $contract->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'baso_date' => $request->baso_dates[$index] ?? null,
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        $contract->refresh();
        $contract->updateStatus();
    });

    ActivityLogger::log(
        'CONTRACT',
        'Updated contract ' . $contract->contract_number
    );

    return redirect()
        ->route('contracts.show', $contract->id)
        ->with('success', 'Contract updated successfully.');
    }

    public function exportReport(string $type): StreamedResponse
{
    $allowedTypes = [
        'current',
        'closed',
    ];

    if (!in_array($type, $allowedTypes)) {
        abort(404);
    }

    $user = auth()->user();

    $query = Contract::with([
        'owner',
        'services.service',
    ]);

    if ($type === 'current') {
        $query->whereIn('status', [
            'active',
            'expiring',
            'followup',
        ]);
    }

    if ($type === 'closed') {
    $query->whereIn('status', [
        'expired',
        'terminated',
    ]);
    }

    if ($user->isAccountManager()) {
        $query->where('owner_am_id', $user->id);
    }
    
    if (request()->filled('status')) {
        $query->where('status', request('status'));
    }

    if (request()->filled('account_manager') && !$user->isAccountManager()) {
        $query->where('owner_am_id', request('account_manager'));
    }

    if (request()->filled('search')) {
        $search = trim(request('search'));

        $query->where(function ($q) use ($search) {
            $q->where('contract_name', 'like', "%{$search}%")
                ->orWhere('contract_number', 'like', "%{$search}%")
                ->orWhere('account_number', 'like', "%{$search}%")
                ->orWhere('sid', 'like', "%{$search}%")
                ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                    $ownerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    $fileName = $type === 'current'
        ? 'Current_Contracts_Report.csv'
        : 'Closed_Contracts_Report.csv';

    return response()->streamDownload(function () use ($query) {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Client Name',
            'Contract Number',
            'Account Number',
            'SID',
            'Account Manager',
            'Package / Services',
            'Start Date',
            'End Date',
            'Status',
            'Monthly Value',
        ]);

        $query->orderBy('end_date')->chunk(200, function ($contracts) use ($handle) {
            foreach ($contracts as $contract) {
                $services = $contract->services
                    ? $contract->services->pluck('service.service_name')->implode(', ')
                    : '-';

                $monthlyValue = $contract->services
                    ? $contract->services->sum(function ($contractService) {
                        return (float) (
                            $contractService->monthly_fee
                            ?? $contractService->service?->monthly_fee
                            ?? 0
                        );
                    })
                    : 0;

                fputcsv($handle, [
                    $contract->contract_name ?? '-',
                    $contract->contract_number ?? '-',
                    $contract->account_number ?? '-',
                    $contract->sid ?? '-',
                    $contract->owner?->name ?? '-',
                    $services ?: '-',
                    $contract->start_date
                        ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y')
                        : '-',
                    $contract->end_date
                        ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y')
                        : '-',
                    ucfirst($contract->status ?? '-'),
                    $monthlyValue,
                ]);
            }
        });

        fclose($handle);
    }, $fileName, [
        'Content-Type' => 'text/csv',
    ]);
    }
}