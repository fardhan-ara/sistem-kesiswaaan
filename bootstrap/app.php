<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'wali_kelas' => \App\Http\Middleware\CheckWaliKelas::class,
            'homeroom_teacher' => \App\Http\Middleware\IsHomeroomTeacher::class,
            'handle_errors' => \App\Http\Middleware\HandleServerErrors::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            //
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\HandleServerErrors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 500) {
                \Log::error('HTTP 500 Error: ' . $e->getMessage(), [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Server Error',
                        'message' => 'Terjadi kesalahan sistem'
                    ], 500);
                }
                
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            }
        });
    })->create();
