<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasoFile extends Model
{
    protected $fillable = [

        'contract_id',
        'file_name',
        'file_path',
        'baso_date',
        'uploaded_by',
    ];

    public function contract()
    {
        return $this->belongsTo(
            Contract::class
        );
    }
}   
