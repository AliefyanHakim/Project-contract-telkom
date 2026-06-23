<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        $roleMap = [
            'manager' => User::ROLE_MANAGER,
            'account_manager' => User::ROLE_ACCOUNT_MANAGER,
            'support_inputter' => User::ROLE_SUPPORT_INPUTTER,
            'support_paycall' => User::ROLE_SUPPORT_PAYCALL,
        ];

        $allowedRoleIds = [];

        foreach ($roles as $role) {
            if (isset($roleMap[$role])) {
                $allowedRoleIds[] = $roleMap[$role];
            }
        }

        if (!in_array($user->role_id, $allowedRoleIds)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}