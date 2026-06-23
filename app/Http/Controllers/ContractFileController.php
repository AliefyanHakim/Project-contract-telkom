<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\ContractFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ContractFileController extends Controller
{
        //
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

    public function download(
        ContractFile $file
    )
    {
        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::download(
            $file->file_path,
            $file->file_name
        );
    }
}
