<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\CorsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(LocaleMiddleware::class);

    // Alias middleware (daftar semua di sini)
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class, // ✅ tambahkan ini
    ]);
})

    ->withCommands([
        \App\Console\Commands\SendReminderEmails::class, // <-- ini
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
