{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Global Fonts & Background Styling --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            background-image: url('/images/background2.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-black relative min-h-screen text-white">

    {{-- Global background overlay (dimmer) --}}
    <div class="absolute inset-0 z-0 bg-black opacity-70 pointer-events-none"></div>

    {{-- App content sits on top --}}
    <div class="relative z-10">
        @include('layouts.navigation_role') {{-- Shared top navbar --}}

        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            <aside class="w-64 bg-black text-white border-r border-white flex-shrink-0 flex flex-col">
                @php
                    $role = session('active_role')
                        ?? (Auth::check() && Auth::user()->roles->first() 
                            ? Auth::user()->roles->first()->name 
                            : 'guest');
                @endphp

                @includeIf('layouts.sidebars.sidebar-' . $role)
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
