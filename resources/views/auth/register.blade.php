<x-guest-layout>
    {{-- Success Alert --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4 max-w-md mx-auto">
            {{ session('success') }}
        </div>
    @endif

    {{-- Glass Card --}}
    <div class="w-full max-w-md p-8 rounded-xl glass shadow-xl text-white">


        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    required autofocus autocomplete="name"
                    class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
            </div>

            {{-- Email --}}
            <div class="mt-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    required autocomplete="username"
                    class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            {{-- Password --}}
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" type="password" name="password"
                    required autocomplete="new-password"
                    class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            {{-- Confirm Password --}}
            <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    required autocomplete="new-password"
                    class="mt-1 block w-full rounded-lg border-gray-300 text-black shadow-sm">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
            </div>

            {{-- Already Registered + Submit --}}
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-indigo-200 hover:underline">
                    Already registered?
                </a>

                <x-primary-button class="ml-3">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
