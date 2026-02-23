<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {

            $modulesPath = app_path('Modules');

            if (is_dir($modulesPath)) {
                foreach (scandir($modulesPath) as $module) {

                    if ($module === '.' || $module === '..') {
                        continue;
                    }

                    $routePath = $modulesPath . '/' . $module . '/Routes/api.php';

                    if (file_exists($routePath)) {
                        require $routePath;
                    }
                }
            }
        }
    )
    ->withSchedule(function (Schedule $schedule): void {
        // Clean up expired or revoked refresh tokens and their sessions hourly
        $schedule->command('tokens:prune')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified'    => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'jwt.auth'    => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
            'throttle'    => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle expired JWT token as JSON response
        $exceptions->render(function (TokenExpiredException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token has expired.',
                ], 401);
            }
        });

        // Handle invalid JWT token
        $exceptions->render(function (TokenInvalidException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is invalid.',
                ], 401);
            }
        });

        // Handle generic JWT-related errors
        $exceptions->render(function (JWTException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token error: ' . $e->getMessage(),
                ], 401);
            }
        });
    })
    ->create();
