<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect devotee to Google OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        $clientId = config('services.google.client_id');
        if (empty($clientId) || str_contains($clientId, 'dummy_google_client_id')) {
            return redirect()->route('login')->with(
                'info',
                'Google OAuth is configured with demo placeholder credentials. Please sign in using your standard email/password or update GOOGLE_CLIENT_ID in .env.'
            );
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle incoming Google OAuth callback.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed: '.$e->getMessage());
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));
        $googleId = (string) $googleUser->getId();

        // 1. Check if user already exists with this google_id or email
        $user = User::where('google_id', $googleId)->orWhere('email', $email)->first();

        if ($user) {
            // STRICT SECURITY ENFORCEMENT: Admin accounts cannot be accessed via customer Google Sign-In
            if ($user->is_admin) {
                return redirect()->route('login')->with(
                    'error',
                    'Google Sign-In is strictly for customer devotee accounts. Administrators must authenticate using their email and password.'
                );
            }

            // Link Google ID if not already linked
            if (! $user->google_id) {
                $user->google_id = $googleId;
            }
            if ($googleUser->getAvatar() && ! $user->avatar) {
                $user->avatar = $googleUser->getAvatar();
            }
            $user->save();
        } else {
            // Create new customer account
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Devotee',
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'is_admin' => false,
                'is_active' => true,
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with(
            'success',
            'Har Har Gange! Welcome back, '.$user->name.'.'
        );
    }
}
