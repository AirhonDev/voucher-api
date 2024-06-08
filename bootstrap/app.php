<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: fn () => Route::middleware('api')->group(base_path('routes/api.php'))
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(ForceJsonResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
