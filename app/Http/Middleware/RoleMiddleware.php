<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role); // ✅ selalu lowercase

        // normalize role list ke lowercase juga
        $roles = array_map('strtolower', $roles);

        // Debug (lihat di storage/logs/laravel.log)
        \Log::info('ROLE MIDDLEWARE CHECK', [
            'user_id'       => $user->id,
            'user_role'     => $userRole,
            'roles_allowed' => $roles,
        ]);

       if (!in_array($userRole, $roles)) {
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($userRole === 'member') {
            return redirect()->route('member.dashboard');
        }
        abort(403, 'Unauthorized access');
    }


        return $next($request);
    }
}
