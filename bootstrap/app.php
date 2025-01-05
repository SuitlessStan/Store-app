<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', // Load web routes
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('admin', [
            'auth',
            \App\Http\Middleware\AdminMiddleware::class,
        ]);
//
//        $middleware->group('api', [
//         //   EnsureFrontendRequestsAreStateful::class,
//           // ThrottleRequests::class . ':api',
//            SubstituteBindings::class,
//        ]);
   })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
