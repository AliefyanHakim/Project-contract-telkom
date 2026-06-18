<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $setting = Auth::user()
            ->notificationSetting;

        return view(
            'settings.email-notifications',
            compact('setting')
        );
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'notification_email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        NotificationSetting::updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'notification_email'
                    => $validated['notification_email'],
            ]
        );

        return back()->with(
            'success',
            'Email notification settings updated successfully.'
        );
    }
}