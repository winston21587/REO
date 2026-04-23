<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        'rate_limit_submissions' => \App\Http\Middleware\RateLimitSubmissions::class,
    ]);
        
    $middleware->redirectGuestsTo(fn (Illuminate\Http\Request $request) => route('login'));
    $middleware->redirectUsersTo(function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return route('admin.analytics');
        }
        if ($user && $user->role === 'super_admin') {
            return route('super_admin.analytics');
        }
        return route('home');
    });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->route('login')->with('error', 'Page expired. Please login again.');
        });
    })->create();
