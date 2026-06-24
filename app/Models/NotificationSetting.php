<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'manager_email',
        'daily_schedule',
        'weekly_schedule',
    ];

    public static function settings()
    {
        return self::first();
    }
}