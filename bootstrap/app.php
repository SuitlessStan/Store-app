<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
      //  web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->group('api', [
//            'auth' => \Illuminate\Auth\Middleware\Authenticate::classs,
//            EnsureFrontendRequestsAreStateful::class, // SPA
//            ThrottleRequests::class . ':api',
//            SubstituteBindings::class,
//        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
