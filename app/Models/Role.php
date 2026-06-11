<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const MANAGER = 1;
    const ACCOUNT_MANAGER = 2;
    const SUPPORT_INPUTTER = 3;
    const SUPPORT_PAYCALL = 4;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}