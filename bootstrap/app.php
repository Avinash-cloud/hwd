<?php

use App\Http\Middleware\CheckAdminPermission;
use App\Http\Middleware\EnsureUserHasMembership;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'member' => EnsureUserHasMembership::class,
            'permission' => CheckAdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (BadRequestException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson()) && ! $request->is('404')) {
                return redirect()->route('not-found');
            }
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                if ($e->getStatusCode() === 400 && ! $request->is('404')) {
                    return redirect()->route('not-found');
                }
            }
        });
    })->create();
