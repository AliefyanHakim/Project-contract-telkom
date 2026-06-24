<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'contract_id',
        'billing_period',
        'due_date',
        'amount',
        'payment_status',
        'payment_date',
        'proof_file',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'payment_date' => 'date',
            'amount' => 'decimal:2',
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