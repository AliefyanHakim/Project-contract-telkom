<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'manager_email',
        'daily_schedule',
        'weekly_schedule',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}