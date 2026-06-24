<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $module,
        string $activity
    ): void
    {
        ActivityLog::create([
            'user_id'  => Auth::id(),
            'module'   => $module,
            'activity' => $activity,
        ]);
    }
}