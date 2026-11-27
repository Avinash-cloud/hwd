<?php

namespace App\Support;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class SoftErrorResponder
{
    /**
     * Render any 500-series HTTP exception or unexpected server error softly.
     */
    public static function render(Throwable $exception, Request $request): Response|JsonResponse|null
    {
        if (
            $exception instanceof ValidationException
            || $exception instanceof AuthenticationException
            || $exception instanceof AuthorizationException
            || $exception instanceof TokenMismatchException
        ) {
            return null;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            if ($statusCode < 500 || $statusCode >= 600) {
                return null;
            }
        } else {
            if (config('app.debug')) {
                return null;
            }
            $statusCode = 500;
        }

        $referenceId = 'HB-ERR-'.strtoupper(Str::random(6));

        try {
            Log::error("Soft {$statusCode} Error [{$referenceId}]", [
                'status' => $statusCode,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        } catch (Throwable) {
            // Never let logging failure interrupt soft error rendering.
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'status' => $statusCode,
                'message' => self::friendlyMessage($statusCode),
                'reference_id' => $referenceId,
            ], $statusCode);
        }

        $viewName = match ($statusCode) {
            503 => 'errors.503',
            500 => 'errors.500',
            default => 'errors.5xx',
        };

        try {
            return response()->view($viewName, [
                'statusCode' => $statusCode,
                'referenceId' => $referenceId,
                'exception' => $exception,
            ], $statusCode);
        } catch (Throwable) {
            return response(self::emergencyFallbackHtml($statusCode, $referenceId), $statusCode, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]);
        }
    }

    /**
     * Get a calm, user-safe message without leaking technical internals.
     */
    public static function friendlyMessage(int $statusCode): string
    {
        return match ($statusCode) {
            503 => 'Our sacred portal is undergoing brief maintenance. Please check back in a few moments.',
            502, 504 => 'Our servers experienced a temporary connection delay. Please try refreshing in a moment.',
            default => 'Something unexpected happened on our end while loading this page. Your orders and payments remain safe — please try again shortly.',
        };
    }

    /**
     * Minimal HTML safety fallback if Blade rendering itself is disrupted.
     */
    protected static function emergencyFallbackHtml(int $statusCode, string $referenceId): string
    {
        $message = htmlspecialchars(self::friendlyMessage($statusCode), ENT_QUOTES, 'UTF-8');
        $ref = htmlspecialchars($referenceId, ENT_QUOTES, 'UTF-8');
        $homeUrl = htmlspecialchars(url('/'), ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temporary Pause ({$statusCode}) - Blu Bliss</title>
    <style>
        body { font-family: Georgia, serif; background: #FFFDF7; color: #292524; margin: 0; padding: 32px 16px; display: flex; min-height: 90vh; align-items: center; justify-content: center; }
        .card { max-width: 560px; width: 100%; background: #FFFFFF; border: 1px solid #FDE68A; border-radius: 20px; padding: 36px 28px; text-align: center; box-shadow: 0 10px 25px rgba(120, 53, 15, 0.08); }
        .code { font-size: 56px; font-weight: 800; color: #B45309; margin: 0 0 8px; }
        h1 { font-size: 24px; margin: 0 0 12px; color: #881337; text-align: center; }
        p { font-family: sans-serif; font-size: 14px; line-height: 1.6; color: #57534E; margin: 0 0 20px; }
        .btn { display: inline-block; padding: 10px 22px; background: #D97706; color: #FFFFFF; text-decoration: none; border-radius: 999px; font-family: sans-serif; font-size: 13px; font-weight: 700; }
        .meta { margin-top: 20px; font-family: monospace; font-size: 11px; color: #78716C; }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">{$statusCode}</div>
        <h1>A Gentle Pause on Our End</h1>
        <p>{$message}</p>
        <a href="{$homeUrl}" class="btn">Return to Home</a>
        <div class="meta">Reference: {$ref} &bull; Support: +91 7042482555 / sales@haridwarbliss.com</div>
    </div>
</body>
</html>
HTML;
    }
}
