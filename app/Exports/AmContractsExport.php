<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AmContractsExport implements
    FromCollection,
    WithHeadings
{
    protected $amId;

    public function __construct($amId)
    {
        $this->amId = $amId;
    }

    public function collection()
    {
        return Contract::with([
            'owner',
            'services.service'
        ])
        ->where(
            'owner_am_id',
            $this->amId
        )
        ->get()
        ->map(function ($contract) {

            return [

                'Client Name'
                    => $contract->contract_name,

                'Contract Number'
                    => $contract->contract_number,

                'Package'
                    => $contract->services
                        ->pluck('service.service_name')
                        ->implode(', '),

                'Start Date'
                    => optional(
                        $contract->start_date
                    )->format('d/m/Y'),

                'End Date'
                    => optional(
                        $contract->end_date
                    )->format('d/m/Y'),

                'Status'
                    => $contract->status,

                'Account Manager'
                    => $contract->owner?->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Contract Number',
            'Package',
            'Start Date',
            'End Date',
            'Status',
            'Account Manager',
        ];
    }
}