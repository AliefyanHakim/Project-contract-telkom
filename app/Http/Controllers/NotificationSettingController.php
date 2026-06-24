<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAccountManager() && !$user->isSupportInputter()) {
            abort(403, 'Hanya Account Manager dan Support Inputter yang dapat mengatur email reminder.');
        }

        $settings = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'manager_email' => $user->email,
                'daily_schedule' => '08:00',
                'weekly_schedule' => 'monday_morning',
            ]
        );

        return view('settings.email-notifications', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAccountManager() && !$user->isSupportInputter()) {
            abort(403, 'Hanya Account Manager dan Support Inputter yang dapat mengatur email reminder.');
        }

        $validated = $request->validate([
            'manager_email' => [
                'required',
                'email',
                'max:255',
            ],

            'daily_schedule' => [
                'required',
                'max:20',
            ],

            'weekly_schedule' => [
                'required',
                'max:50',
            ],
        ]);

        $settings = NotificationSetting::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'manager_email' => $user->email,
                'daily_schedule' => '08:00',
                'weekly_schedule' => 'monday_morning',
            ]
        );

        $settings->update([
            'manager_email' => $validated['manager_email'],
            'daily_schedule' => $validated['daily_schedule'],
            'weekly_schedule' => $validated['weekly_schedule'],
        ]);

        ActivityLogger::log(
            'EMAIL_NOTIFICATION',
            'Updated email notification settings'
        );

        return back()->with('success', 'Email reminder settings updated successfully.');
    }
}