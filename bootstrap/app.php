<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.key' => \App\Http\Middleware\CheckApiKey::class,
            'admin.area' => \App\Http\Middleware\RedirectReviewersToAuditing::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'query_user' => \App\Http\Middleware\QueryUserMiddleware::class,
            'reviewer' => \App\Http\Middleware\ReviewerMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                if ($request->is('portal/*') || $request->is('portal')) {
                    return route('customer.login');
                }
                if ($request->is('query/*') || $request->is('query')) {
                    return route('query.login');
                }
                if ($request->is('reviewer/*') || $request->is('reviewer')) {
                    return route('customer.login');
                }
                return route('login');
            },
            users: function ($request) {
                if ($request->user() && $request->user()->role === 'customer') {
                    return route('customer.search');
                }
                if ($request->user() && $request->user()->role === 'query_user') {
                    return route('query.dashboard');
                }
                if ($request->user() && $request->user()->role === 'reviewer') {
                    return route('medical-auditing.index');
                }
                return route('dashboard');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Sentry Integration for error tracking
        Integration::handles($exceptions);
    })->create();
