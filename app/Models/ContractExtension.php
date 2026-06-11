<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractExtension extends Model
{
    protected $fillable = [
        'contract_id',
        'old_end_date',
        'new_end_date',
        'reason',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'old_end_date' => 'date',
            'new_end_date' => 'date',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}