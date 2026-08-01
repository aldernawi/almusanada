<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        
        // Admin has all permissions
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user has the specific permission
        if (!$user->hasPermission($permission)) {
            abort(403, 'ليست لديك صلاحية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
