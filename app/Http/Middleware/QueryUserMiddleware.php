<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class QueryUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'query_user') {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect()->route('customer.search');
        }

        return redirect()->route('query.login');
    }
}
