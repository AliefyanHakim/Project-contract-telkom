<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTransferHistory extends Model
{
    protected $table = 'contract_transfer_history';

    protected $fillable = [
        'contract_id',
        'from_am_id',
        'to_am_id',
        'transferred_by',
        'notes',
        'transfer_date',
    ];

    protected function casts(): array
    {
        return [
            'transfer_date' => 'datetime',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function fromAM()
    {
        return $this->belongsTo(User::class, 'from_am_id');
    }

    public function toAM()
    {
        return $this->belongsTo(User::class, 'to_am_id');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}