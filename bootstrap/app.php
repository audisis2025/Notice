<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        //api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'business.owner' => \App\Http\Middleware\CheckBusinessOwner::class,
            'business.active' => \App\Http\Middleware\CheckBusinessActive::class,
            'package.active' => \App\Http\Middleware\CheckActivePackage::class,
            'package.feature' => \App\Http\Middleware\CheckPackageFeature::class,
            'log.activity' => \App\Http\Middleware\LogActivity::class,
            'ensure.business' => \App\Http\Middleware\EnsureHasBusiness::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
