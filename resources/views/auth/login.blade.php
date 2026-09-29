<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome Back</h1>
        <p class="mt-1 text-sm text-slate-500">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-[#D97706] hover:text-[#F97316] hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#D97706] shadow-sm focus:ring-[#D97706] focus:ring-offset-0 transition" name="remember">
                <span class="ms-2 text-sm text-slate-600 select-none">{{ __('Remember me on this device') }}</span>
            </label>
        </div>

        <div>
            <x-primary-button class="w-full">
                {{ __('Sign in to account') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <p class="text-sm text-slate-600">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="font-semibold text-[#D97706] hover:text-[#F97316] hover:underline ms-1">
                {{ __('Sign up for free') }}
            </a>
        </p>
    </div>
</x-guest-layout>
