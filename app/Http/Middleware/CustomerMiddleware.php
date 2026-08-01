<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login');
        }

        $user = Auth::user();
        
        // Admin users should not access customer routes
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Query users should not access customer routes
        if ($user->role === 'query_user') {
            return redirect()->route('query.dashboard');
        }

        // Customer users can access
        if ($user->role === 'customer') {
            return $next($request);
        }

        return redirect()->route('customer.login');
    }
}
