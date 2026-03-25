<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $e, $request) {
            return back()
                ->withInput($request->except(['cover_image', 'cover_image_keep']))
                ->with('error_modal', 'Ukuran file yang diupload terlalu besar. Maksimal ukuran per file adalah 1MB. Silakan kompres gambar terlebih dahulu dan coba lagi.');
        });
    })->create();
