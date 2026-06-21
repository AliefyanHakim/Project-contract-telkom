<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([

            'name' => [
                'required',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {

            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->save();

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }
}