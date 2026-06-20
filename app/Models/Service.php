<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_name',
        'installation_fee',
        'monthly_fee',
        'status',
    ];

    public function contractServices()
    {
        return $this->hasMany(
            ContractService::class
        );
    }
}