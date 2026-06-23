<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contract;
use App\Models\User;
use App\Models\Service;
use App\Models\ContractService;
use App\Models\ContractFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ContractController extends Controller
{

private function ensureContractAccess(Contract $contract): void
{
    $user = Auth::user();

    if ($user->isAccountManager() && $contract->owner_am_id !== $user->id) {
        abort(403, 'Anda tidak memiliki akses ke kontrak ini.');
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
        $query->where('status', 'active');

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
            'services.service'
        ])
        ->where('status', 'expired');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAccountManager()) {

            $query->where(
                'owner_am_id',
                $user->id
            );
        }

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
        | Filter Account Manager
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
        | Filter Package
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

        $contracts = $query
            ->orderByDesc('end_date')
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
        $fullPath = storage_path(
            'app/' . $file->file_path
        );

        if (!File::exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }

    public function updateStatus()
    {
        $daysRemaining = now()
            ->startOfDay()
            ->diffInDays(
                $this->end_date,
                false
            );

        if ($daysRemaining < 0) {

            $this->status = 'expired';

        } elseif ($daysRemaining <= 7) {

            $this->status = 'followup';

        } elseif ($daysRemaining <= 30) {

            $this->status = 'expiring';

        } else {

            $this->status = 'active';
        }

        $this->save();
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
        $validated = $request->validate([

            'contract_number' => [
                'required',
                'max:100',
                'unique:contracts,contract_number'
            ],

            'contract_name' => [
                'required',
                'max:255'
            ],

            'customer_id_number' => [
                'nullable',
                'max:100'
            ],

            'telkom_name' => [
                'nullable',
                'max:255'
            ],

            'telkom_position' => [
                'nullable',
                'max:255'
            ],

            'telkom_unit' => [
                'nullable',
                'max:255'
            ],

            'customer_address' => [
                'nullable'
            ],

            'customer_npwp' => [
                'nullable',
                'max:100'
            ],

            'customer_pic_name' => [
                'nullable',
                'max:255'
            ],

            'customer_pic_position' => [
                'nullable',
                'max:255'
            ],

            'customer_phone' => [
                'nullable',
                'max:50'
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'owner_am_id' => [
                'required',
                'exists:users,id'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date'
            ],

            'signing_date' => [
                'nullable',
                'date'
            ],

            'signing_location' => [
                'nullable',
                'max:255'
            ],

            'services' => [
                'required',
                'array',
                'min:1'
            ],

            'services.*' => [
                'required',
                'exists:services,id'
            ],

            'file' => 'nullable|file|max:10240'
        ]);

        $contract = null;

        DB::transaction(function () use (
            $validated,
            $request,
            &$contract) {

            $contract = Contract::create([

                'contract_number'
                    => $validated['contract_number'],

                'contract_name'
                    => $validated['contract_name'],

                'customer_id_number'
                    => $validated['customer_id_number'] ?? null,

                'telkom_name'
                    => $validated['telkom_name'] ?? null,

                'telkom_position'
                    => $validated['telkom_position'] ?? null,

                'telkom_unit'
                    => $validated['telkom_unit'] ?? null,

                'customer_address'
                    => $validated['customer_address'] ?? null,

                'customer_npwp'
                    => $validated['customer_npwp'] ?? null,

                'customer_pic_name'
                    => $validated['customer_pic_name'] ?? null,

                'customer_pic_position'
                    => $validated['customer_pic_position'] ?? null,

                'customer_phone'
                    => $validated['customer_phone'] ?? null,

                'customer_email'
                    => $validated['customer_email'] ?? null,

                'signing_date'
                    => $validated['signing_date'] ?? null,

                'signing_location'
                    => $validated['signing_location'] ?? null,

                'owner_am_id'
                    => $validated['owner_am_id'],

                'start_date'
                    => $validated['start_date'],

                'end_date'
                    => $validated['end_date'],

                'status'
                    => 'active',

                'created_by'
                    => Auth::id(),
                
                'contract_file' => [
                    'nullable',
                    'file',
                    'mimes:pdf,doc,docx',
                    'max:10240'
                ],
            ]);
            if ($request->hasFile('file')) {

                $path = $request
                    ->file('file')
                    ->store('contracts');

                ContractFile::create([
                    'contract_id' => $contract->id,
                    'file_name' => $request
                        ->file('file')
                        ->getClientOriginalName(),
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(),
                ]);
            }
            

            $contract->updateStatus();

            foreach ($validated['services'] as $serviceId) {

                ContractService::create([

                    'contract_id'
                        => $contract->id,

                    'service_id'
                        => $serviceId,
                ]);
            }
        });

    return redirect()
        ->route(
            'contracts.show',
            $contract->id
        )
        ->with(
            'success',
            'Contract created successfully.'
        );
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
        'files'
    ]);

    return view(
        'contracts.detail-contract',
        compact('contract')
    );
    }

    public function destroy(Contract $contract)
    {
    $contract->delete();

    return redirect()
        ->route('contracts.index')
        ->with(
            'success',
            'Contract deleted successfully.'
        );
    }

    public function edit(Contract $contract)
    {
        $this->ensureContractAccess($contract);

    $contract->load([
        'services'
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

    public function update(
        Request $request,
        Contract $contract
    )    
    {
        $this->ensureContractAccess($contract);

        if (Auth::user()->isSupportPaycall()) {
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

            return redirect()
                ->route('contracts.show', $contract->id)
                ->with('success', 'Start date dan end date kontrak berhasil diperbarui.');
        }

        $validated = $request->validate([

            'contract_name' => [
                'required',
                'max:255'
            ],

            'customer_id_number' => [
                'nullable'
            ],

            'customer_address' => [
                'nullable'
            ],

            'customer_npwp' => [
                'nullable'
            ],

            'customer_pic_name' => [
                'nullable'
            ],

            'customer_pic_position' => [
                'nullable'
            ],

            'customer_phone' => [
                'nullable'
            ],

            'customer_email' => [
                'nullable',
                'email'
            ],

            'owner_am_id' => [
                'required',
                'exists:users,id'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'end_date' => [
                'required',
                'date'
            ],

            'services' => [
                'required',
                'array',
                'min:1'
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $request,
            $contract
        ) {

            /*
            |--------------------------------------------------------------------------
            | Data contract
            |--------------------------------------------------------------------------
            */

            $data = [

                'contract_name'
                    => $validated['contract_name'],

                'customer_id_number'
                    => $validated['customer_id_number'] ?? null,

                'customer_address'
                    => $validated['customer_address'] ?? null,

                'customer_npwp'
                    => $validated['customer_npwp'] ?? null,

                'customer_pic_name'
                    => $validated['customer_pic_name'] ?? null,

                'customer_pic_position'
                    => $validated['customer_pic_position'] ?? null,

                'customer_phone'
                    => $validated['customer_phone'] ?? null,

                'customer_email'
                    => $validated['customer_email'] ?? null,

                'owner_am_id'
                    => $validated['owner_am_id'],

                'start_date'
                    => $validated['start_date'],

                'end_date'
                    => $validated['end_date'],
            ];

           
            $contract->update($data);

            $contract->refresh();

            $contract->updateStatus();

            /*
            |--------------------------------------------------------------------------
            | Hapus service lama
            |--------------------------------------------------------------------------
            */

            ContractService::where(
                'contract_id',
                $contract->id
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | Hapus service lama
            |--------------------------------------------------------------------------
            */

            ContractService::where(
                'contract_id',
                $contract->id
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | Simpan service baru
            |--------------------------------------------------------------------------
            */

            foreach ($validated['services'] as $serviceId) {

                ContractService::create([

                    'contract_id'
                        => $contract->id,

                    'service_id'
                        => $serviceId,
                ]);
            }
        });

        return redirect()
            ->route(
                'contracts.show',
                $contract->id
            )
            ->with(
                'success',
                'Contract updated successfully.'
            );
    }
    
}