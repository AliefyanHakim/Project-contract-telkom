<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTransferRequest extends Model
{
    protected $fillable = [
        'contract_id',
        'requested_by',
        'current_am_id',
        'target_am_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function currentAM()
    {
        return $this->belongsTo(User::class, 'current_am_id');
    }

    public function targetAM()
    {
        return $this->belongsTo(User::class, 'target_am_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}