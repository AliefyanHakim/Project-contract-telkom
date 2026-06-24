<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        $field = filter_var(
            $credentials['login'],
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'name';

        $remember = $request->boolean('remember');

        if (
            Auth::attempt([
                $field => $credentials['login'],
                'password' => $credentials['password'],
                'status' => 'active',
            ], $remember)
        ) {
            $request->session()->regenerate();

            ActivityLogger::log(
                'AUTH',
                'Login'
            );
            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors([
                'login' => 'Username / Email atau Password salah.',
            ])
            ->onlyInput('login');
    }

    public function logout(Request $request)
    {
        ActivityLogger::log(
            'AUTH',
            'Logout'
        );
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([

            'name' => [
                'required',
                'max:100'
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'min:8',
                'confirmed'
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

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }
}