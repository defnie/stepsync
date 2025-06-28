<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Soulstep') }}</title>

    <!-- Tailwind + Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
</head>

<body class="text-white">
    {{-- Video Background --}}
    <video autoplay muted loop class="bg-video">
        <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4" />
        Your browser does not support HTML5 video.
    </video>

    {{-- Page Content --}}
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        {{ $slot }}
    </div>
</body>
</html>
