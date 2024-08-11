<?php

use App\Http\Middleware\AdminUserTypeMiddleware;
use App\Http\Middleware\AuthUserType;
use App\Http\Middleware\WorkerUserTypeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'deptHeadUserType' => AuthUserType::class,
            'adminUserType' => AdminUserTypeMiddleware::class,
            'workerUserType' => WorkerUserTypeMiddleware::class,
     ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
