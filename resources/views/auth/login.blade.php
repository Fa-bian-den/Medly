<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>

            <!-- Google OAuth button (dentro del mismo div, mantiene márgenes) -->
            <a href="{{ route('google.redirect') }}"
               class="inline-flex items-center px-4 py-2 ms-3 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 me-2" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill="#EA4335" d="M24 9.5c3.9 0 6.6 1.7 8.6 3.2l6.3-6.3C34 3 29.5 1 24 1 14.8 1 6.8 6.7 3.1 14.9l7.3 5.6C12.9 15.1 18 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.6 24.5c0-1.6-.1-2.8-.4-4.1H24v8h12.7c-.5 2.8-2 5.2-4.3 6.8l6.6 5.1C44.6 37.6 46.6 31.9 46.6 24.5z"/>
                    <path fill="#FBBC05" d="M10.4 28.1c-.9-2.7-.9-5.6 0-8.3L3.1 14.2C1.1 18.7 0 22.8 0 27.3s1.1 8.6 3.1 13.1l7.3-5.6z"/>
                    <path fill="#34A853" d="M24 46c5.5 0 10.3-1.8 13.8-5l-6.6-5.1c-2 1.3-4.6 2.1-7.2 2.1-6 0-11.1-5.6-11.6-12.7L3.1 34.4C6.8 42.3 14.8 48 24 48z"/>
                </svg>

                <span>Iniciar sesión con Google</span>
            </a>
        </div>
    </form>
</x-guest-layout>