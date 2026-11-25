<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
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
            $membershipFeeFormatted = number_format((float) SiteSetting::get('membership_fee', 500));
            $validityYears = (int) SiteSetting::get('membership_validity_years', 5);

            return redirect()->route('membership.join')->with(
                'warning',
                "Purchasing sacred products is exclusive to active Blu Bliss members. Please join our {$validityYears}-Year Sacred Membership for ₹{$membershipFeeFormatted} to proceed."
            );
        }

        return $next($request);
    }
}
