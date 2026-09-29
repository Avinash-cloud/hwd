<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to your account.');
        }

        if (! $user->hasActiveMembership() && ! $user->is_admin) {
            return redirect()->route('membership.join')->with(
                'warning',
                'Purchasing sacred products is exclusive to active Haridwar Bliss members. Please join our 5-Year Sacred Membership for ₹500 to proceed.'
            );
        }

        return $next($request);
    }
}
