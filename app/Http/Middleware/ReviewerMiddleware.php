<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ReviewerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login');
        }

        $user = Auth::user();
        
        // Only reviewers can access
        if ($user->role === 'reviewer') {
            return $next($request);
        }

        // Redirect other roles to their appropriate dashboards
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'query_user') {
            return redirect()->route('query.dashboard');
        }

        return redirect()->route('customer.login');
    }
}
