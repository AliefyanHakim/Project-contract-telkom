<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contract_number',
        'contract_name',
        'owner_am_id',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_am_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files()
    {
        return $this->hasMany(ContractFile::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function extensions()
    {
        return $this->hasMany(ContractExtension::class);
    }

    public function transferRequests()
    {
        return $this->hasMany(
            ContractTransferRequest::class
        );
    }

    public function transferHistory()
    {
        return $this->hasMany(
            ContractTransferHistory::class
        );
    }
}