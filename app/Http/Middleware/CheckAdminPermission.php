<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_admin) {
            return redirect()->route('login')->with('warning', 'Restricted administrative area. Please sign in with staff credentials.');
        }

        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Your administrative account has been deactivated. Please contact the administrator.');
        }

        if ($permission && ! $user->hasPermission($permission)) {
            abort(403, 'Unauthorized. You do not possess the required permission ['.$permission.'] to access this administrative section.');
        }

        return $next($request);
    }
}
