<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractFile extends Model
{
    protected $fillable = [
        'contract_id',
        'file_name',
        'file_path',
        'uploaded_by',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}