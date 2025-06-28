<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        video.bg-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .glass {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>

    {{-- Video Background --}}
    <video autoplay muted loop class="bg-video">
        <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    {{-- Login Form --}}
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <div class="w-full max-w-md p-8 rounded-xl glass shadow-xl text-white">
            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/soulstep-logo.png') }}" alt="Soulstep Logo" class="h-24">
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="username"
                        class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                {{-- Password --}}
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input id="password" type="password" name="password"
                        required autocomplete="current-password"
                        class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                {{-- Remember Me --}}
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm">Remember me</span>
                    </label>
                </div>

                {{-- Submit & Forgot Password --}}
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-200 hover:underline" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif

                    <x-primary-button class="ml-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

            {{-- Register Link --}}
            <p class="mt-4 text-center text-sm text-white">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-300 hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</x-guest-layout>
