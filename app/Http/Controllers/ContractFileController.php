<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\ContractFile;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ContractFileController extends Controller
{
        //
    private function ensureContractAccess(Contract $contract)
    {
        $user = Auth::user();

        if (
            $user->role_id == User::ROLE_ACCOUNT_MANAGER &&
            $contract->owner_am_id != $user->id
        ) {
            abort(403);
        }
    }

        public function store(
        Request $request,
        Contract $contract
    )
    {
        $path = $request
            ->file('file')
            ->store('contracts');

        ContractFile::create([
            'contract_id' => $contract->id,
            'file_name'   => $request
                ->file('file')
                ->getClientOriginalName(),
            'file_path'   => $path,
            'uploaded_by' => Auth::id(),
        ]);

        ActivityLogger::log(
            'FILE',
            'Uploaded file ' .
            $request->file('file')->getClientOriginalName()
        );
    }

    public static function upload(
    Contract $contract,
    $file
    ) {
        $path = $file->store('contracts');

        return self::create([
            'contract_id' => $contract->id,
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $path,
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function download(ContractFile $file)
    {
        $this->ensureContractAccess(
        $file->contract
        );

        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found');
        }

        ActivityLogger::log(
            'FILE',
            'Downloaded file ' . $file->file_name
        );

        return Storage::download(
            $file->file_path,
            $file->file_name
        );
    }
}
