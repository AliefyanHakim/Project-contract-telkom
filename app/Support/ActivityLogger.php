<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $module, string $activity): void
    {
        $userId = Auth::id();

        if (!$userId) {
            $userId = User::where('role_id', User::ROLE_MANAGER)
                ->orderBy('id')
                ->value('id');
        }

        if (!$userId) {
            return;
        }

        ActivityLog::create([
            'user_id' => $userId,
            'module' => $module,
            'activity' => $activity,
        ]);
    }
}