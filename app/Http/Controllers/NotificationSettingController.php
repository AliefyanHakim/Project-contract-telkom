<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationSetting;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $settings = NotificationSetting::first();

        if (!$settings) {

            $settings = NotificationSetting::create([
                'manager_email' => '',
                'daily_schedule' => '08:00',
                'weekly_schedule' => 'monday_morning',
            ]);
        }

        return view(
            'settings.email-notifications',
            compact('settings')
        );
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'manager_email' => [
                'required',
                'email'
            ],

            'daily_schedule' => [
                'required',
                'in:08:00,09:00,10:00'
            ],

            'weekly_schedule' => [
                'required',
                'in:monday_morning,monday_afternoon,friday_morning'
            ],
        ]);

        $settings = NotificationSetting::first();

        $settings->update([
            'manager_email' => $validated['manager_email'],
            'daily_schedule' => $validated['daily_schedule'],
            'weekly_schedule' => $validated['weekly_schedule'],
        ]);

        return back()->with(
            'success',
            'Notification settings updated successfully.'
        );
    }
}