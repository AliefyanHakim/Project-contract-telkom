<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\ContractFile;


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
            'uploaded_by' => auth()->id(),
        ]);
    }
}
