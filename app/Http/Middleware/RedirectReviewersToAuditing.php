<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectReviewersToAuditing
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isReviewer()) {
            abort(403, 'غير مصرح لك بالوصول إلى لوحة الإدارة.');
        }

        return $next($request);
    }
}
