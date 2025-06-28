@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@push('styles')
<style>
    body {
        background-image: url('/images/background2.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    input, select, textarea {
        background-color: rgba(255, 255, 255, 0.95);
        color: #000;
    }
</style>
@endpush

@section('content')
<h1 class="text-3xl font-bold mb-6 text-white">Manajemen Kelas</h1>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

{{-- Classes In Session --}}
@if ($classesInSession->count())
    <div class="mb-6 text-white">
        <h2 class="text-xl font-semibold mb-2">🔥 Kelas Sedang Berlangsung</h2>
        <div class="flex flex-col md:flex-row gap-4">
           @foreach ($classesInSession as $class)
                <div class="w-full glass-card rounded-xl p-6 flex flex-col md:flex-row gap-4 text-white">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold">{{ $class->title }}</h3>
                        <p class="text-sm">👤 {{ $class->instructor->name }}</p>
                        <p class="text-sm">📍 {{ $class->location }}</p>
                        <p class="text-sm">
                            🗓️ {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
                            🕐 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                        </p>
                        <span class="inline-block mt-2 px-2 py-1 text-xs bg-green-200 text-green-800 rounded">Sedang Berlangsung</span>
                    </div>
                    <div>
                        @php
                            $youtubeId = null;
                            $video = $class->videos->first();
                            if ($video && preg_match('/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video->video_url, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        @endphp
                        @if ($youtubeId)
                            <iframe width="240" height="135" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen class="rounded"></iframe>
                        @else
                            <p class="text-xs text-red-500">Link video tidak valid</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="mb-6 p-4 bg-yellow-50 rounded text-black">
        🚫 Tidak ada kelas yang sedang berlangsung.
    </div>
@endif

{{-- Add New Class Form --}}
<div class="glass-card p-6 rounded-xl shadow mb-6 text-white">
    <h2 class="text-xl font-semibold mb-4">➕ Tambah Kelas Baru</h2>
    <form action="{{ route('instructor.classes.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="title" placeholder="Judul Kelas" class="p-2 rounded" required>
            <input type="text" name="location" placeholder="Lokasi" class="p-2 rounded" required>

            <select name="style" class="p-2 rounded" required>
                <option value="">Pilih Style Koreografi</option>
                @foreach($choreographies->pluck('style')->unique() as $style)
                    <option value="{{ $style }}">{{ $style }}</option>
                @endforeach
            </select>

            <select name="difficulty" class="p-2 rounded" required>
                <option value="">Pilih Tingkat Kesulitan</option>
                @foreach($choreographies->pluck('difficulty')->unique() as $difficulty)
                    <option value="{{ $difficulty }}">{{ $difficulty }}</option>
                @endforeach
            </select>

            <input type="url" name="video_url" placeholder="Link YouTube (https://...)" class="p-2 rounded" required>
            <input type="date" name="date" class="p-2 rounded" required>
            <input type="time" name="start_time" class="p-2 rounded" required>
            <input type="time" name="end_time" class="p-2 rounded" required>
            <input type="number" name="max_slot" min="1" placeholder="Slot Maks" class="p-2 rounded" required>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- Other Classes --}}
<h2 class="text-xl font-semibold mb-2 text-white">📌 Kelas Lainnya</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
@foreach ($otherClasses as $class)
    <div class="glass-card rounded-xl p-6 flex flex-col md:flex-row gap-4 text-white">
        <div class="flex-1">
            <h3 class="text-lg font-bold">{{ $class->title }}</h3>
            <p class="text-sm">👤 {{ $class->instructor->name }}</p>
            <p class="text-sm">📍 {{ $class->location }}</p>
            <p class="text-sm">
                📅 {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
                🕒 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
            </p>

            <div class="mt-4 flex flex-col gap-2">
                <button onclick="document.getElementById('edit-form-{{ $class->id }}').classList.toggle('hidden')" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                    🛠 Edit
                </button>

                <form action="{{ route('instructor.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this class?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        🚫 Cancelled
                    </button>
                </form>
            </div>

            {{-- Edit Form --}}
            <div id="edit-form-{{ $class->id }}" class="mt-4 hidden">
                <form action="{{ route('instructor.classes.update', $class->id) }}" method="POST" class="space-y-2">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" value="{{ $class->title }}" class="w-full p-2 rounded" required>
                    <input type="text" name="location" value="{{ $class->location }}" class="w-full p-2 rounded" required>
                    <input type="date" name="date" value="{{ $class->date }}" class="w-full p-2 rounded" required>
                    <input type="time" name="start_time" value="{{ $class->start_time }}" class="w-full p-2 rounded" required>
                    <input type="time" name="end_time" value="{{ $class->end_time }}" class="w-full p-2 rounded" required>
                    <input type="number" name="max_slot" value="{{ $class->max_slot }}" class="w-full p-2 rounded" required>

                    <select name="choreography_id" class="w-full p-2 rounded" required>
                        @foreach($choreographies as $choreo)
                            <option value="{{ $choreo->id }}" @selected($class->choreography_id == $choreo->id)>
                                {{ $choreo->style }} ({{ $choreo->difficulty }})
                            </option>
                        @endforeach
                    </select>

                    <input type="url" name="video_url" value="{{ optional($class->videos->first())->video_url }}" class="w-full p-2 rounded" placeholder="YouTube Link (https://...)" required>

                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Update</button>
                </form>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between">
            @php
                $youtubeId = null;
                $video = $class->videos->first();
                if ($video && preg_match('/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video->video_url, $matches)) {
                    $youtubeId = $matches[1];
                }
            @endphp

            @if ($youtubeId)
                <iframe width="240" height="135" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen class="rounded mb-2"></iframe>
            @else
                <p class="text-xs text-red-500">Invalid YouTube link</p>
            @endif

            <div class="text-xl font-bold mt-2 text-white bg-black bg-opacity-30 px-4 py-2 rounded">
                🎟 Slots: {{ $class->bookings->count() }}/{{ $class->max_slot }}
            </div>

            @php
                $now = \Carbon\Carbon::now();
                $start = \Carbon\Carbon::parse("{$class->date} {$class->start_time}");
                $end = \Carbon\Carbon::parse("{$class->date} {$class->end_time}");
            @endphp

            @if ($now->lt($start))
                <span class="mt-2 px-3 py-1 text-sm font-semibold bg-yellow-300 text-black rounded">Not Started</span>
            @elseif ($now->between($start, $end))
                <span class="mt-2 px-3 py-1 text-sm font-semibold bg-green-400 text-white rounded">Ongoing</span>
            @else
                <span class="mt-2 px-3 py-1 text-sm font-semibold bg-gray-400 text-white rounded">Finished</span>
            @endif
        </div>
    </div>
@endforeach
</div>
@endsection
