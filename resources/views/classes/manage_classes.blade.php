@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
    <h1 class="text-3xl font-bold mb-6 font-[Poppins]">Manajemen Kelas</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Create New Class Form --}}
    <div class="bg-white p-6 rounded-xl shadow mb-6 font-[Poppins]">
        <h2 class="text-xl font-semibold mb-4">➕ Tambah Kelas Baru</h2>
        <form action="{{ route('classes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="title" placeholder="Judul Kelas" class="p-2 border rounded" required>
                <input type="text" name="location" placeholder="Lokasi" class="p-2 border rounded" required>

                <select name="instructor_id" class="p-2 border rounded" required>
                    <option value="">Pilih Instruktur</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </select>

                <select name="style" class="p-2 border rounded" required>
                    <option value="">Pilih Style Koreografi</option>
                    @foreach($choreographies->pluck('style')->unique() as $style)
                        <option value="{{ $style }}">{{ $style }}</option>
                    @endforeach
                </select>

                <select name="difficulty" class="p-2 border rounded" required>
                    <option value="">Pilih Tingkat Kesulitan</option>
                    @foreach($choreographies->pluck('difficulty')->unique() as $difficulty)
                        <option value="{{ $difficulty }}">{{ $difficulty }}</option>
                    @endforeach
                </select>

                <input type="url" name="video_url" placeholder="Link YouTube (https://...)" class="p-2 border rounded" required>
                <input type="date" name="date" class="p-2 border rounded" required>
                <input type="time" name="start_time" class="p-2 border rounded" required>
                <input type="time" name="end_time" class="p-2 border rounded" required>
                <input type="number" name="max_slot" min="1" placeholder="Slot Maks" class="p-2 border rounded" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Class Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-[Poppins]">
        @foreach ($classes as $class)
            <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col space-y-3">
                <div class="flex justify-between">
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold">{{ $class->title }}</h2>
                        <p class="text-md text-gray-700">👤 {{ $class->instructor->name }}</p>

                        <div class="flex items-center space-x-4">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm">{{ $class->choreography->style }}</span>

                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <span class="text-xs text-gray-600">{{ $class->choreography->difficulty }}</span>
                        </div>

                        <p class="text-sm text-gray-500">📍 {{ $class->location }}</p>
                        <p class="text-sm text-gray-500">
    🗓️ {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
    🕐 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
</p>

                        </div>

                    <div class="flex flex-col items-end space-y-2">
                        @php
                            $youtubeId = null;
                            if (preg_match('/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $class->choreography->video_url, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        @endphp

                        @if ($youtubeId)
                            <iframe width="200" height="120" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen class="rounded"></iframe>
                        @else
                            <p class="text-xs text-red-500">Link video tidak valid</p>
                        @endif

                        <span class="text-sm text-gray-600">🪑 {{ $class->max_slot }} Slots</span>
                    </div>
                </div>

                {{-- Delete & Edit --}}
                <div class="flex justify-end items-center gap-4">
                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus kelas ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                            🗑️ Hapus
                        </button>

                        
                    </form>
                </div>

                {{-- Edit Form --}}
                <details class="mt-2">
    <summary class="cursor-pointer text-blue-600 hover:underline">✏️ Edit Kelas</summary>
    <form action="{{ route('classes.update', $class->id) }}" method="POST" class="mt-2 space-y-2">
        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ $class->title }}" class="w-full p-2 border rounded" required>
        <input type="text" name="location" value="{{ $class->location }}" class="w-full p-2 border rounded" required>

        {{-- 🧑‍🏫 Instructor select --}}
        <select name="instructor_id" class="w-full p-2 border rounded" required>
            @foreach($instructors as $instructor)
                <option value="{{ $instructor->id }}" @selected($class->instructor_id == $instructor->id)>
                    {{ $instructor->name }}
                </option>
            @endforeach
        </select>

        {{-- Choreography select --}}
        <select name="choreography_id" class="w-full p-2 border rounded" required>
            @foreach($choreographies as $choreo)
                <option value="{{ $choreo->id }}" @selected($class->choreography_id == $choreo->id)>
                    {{ $choreo->style }} ({{ $choreo->difficulty }})
                </option>
            @endforeach
        </select>

        <input type="date" name="date" value="{{ $class->date }}" class="w-full p-2 border rounded" required>
        <input type="time" name="start_time" value="{{ $class->start_time }}" class="w-full p-2 border rounded" required>
        <input type="time" name="end_time" value="{{ $class->end_time }}" class="w-full p-2 border rounded" required>
        <input type="number" name="max_slot" value="{{ $class->max_slot }}" class="w-full p-2 border rounded" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </form>
</details>

            </div>
        @endforeach
    </div>
@endsection
