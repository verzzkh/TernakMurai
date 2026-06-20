<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // ✅ TAMBAHKAN INI

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Daftar file route utama aplikasi (web, console) dan endpoint health check.
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Konfigurasi trusted proxies agar aplikasi bisa membaca header forwarded
        // (umumnya dibutuhkan saat pakai reverse proxy / tunnel seperti ngrok).
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );

        // Alias middleware agar bisa dipakai sebagai string di file route.
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'peternak' => \App\Http\Middleware\PeternakMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
