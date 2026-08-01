<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectReviewersToAuditing
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->isReviewer() || $user->isViewer())) {
            return redirect()->route('medical-auditing.index');
        }

        return $next($request);
    }
}
