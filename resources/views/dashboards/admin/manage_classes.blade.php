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
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    table input,
    table select {
        background-color: rgba(255, 255, 255, 0.8);
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
                            <p class="text-xs text-red-400">Link video tidak valid</p>
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

{{-- =================== TABLE: OTHER CLASSES =================== --}}
<div class="glass-card p-4 rounded-xl overflow-x-auto text-white">
    <h2 class="text-xl font-semibold mb-4">📌 Kelas Lainnya</h2>
    <table class="w-full table-auto border-collapse border border-white text-xs">
        <thead class="bg-white bg-opacity-20 text-white">
            <tr>
                <th class="border px-2 py-1">Judul</th>
                <th class="border px-2 py-1">Lokasi</th>
                <th class="border px-2 py-1">Tanggal</th>
                <th class="border px-2 py-1">Jam</th>
                <th class="border px-2 py-1">Slot</th>
                <th class="border px-2 py-1">Koreografi</th>
                <th class="border px-2 py-1">Status</th>
                <th class="border px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($otherClasses as $class)
            <tr>
                <form action="{{ route('admin.manage_classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td class="border px-2 py-1">
                        <input type="text" name="title" value="{{ $class->title }}" class="w-full p-1 border rounded text-xs" required>
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" name="location" value="{{ $class->location }}" class="w-full p-1 border rounded text-xs" required>
                    </td>
                    <td class="border px-2 py-1">
                        <input type="date" name="date" value="{{ $class->date }}" class="w-full p-1 border rounded text-xs" required>
                    </td>
                    <td class="border px-2 py-1">
                        <input type="time" name="start_time" value="{{ $class->start_time }}" class="w-full p-1 border rounded text-xs" required>
                        <input type="time" name="end_time" value="{{ $class->end_time }}" class="w-full p-1 border rounded text-xs" required>
                    </td>
                    <td class="border px-2 py-1">
                        <input type="number" name="max_slot" value="{{ $class->max_slot }}" class="w-full p-1 border rounded text-xs" required>
                    </td>
                    <td class="border px-2 py-1">
                        <select name="choreography_id" class="w-full p-1 border rounded text-xs" required>
                            @foreach($choreographies as $choreo)
                                <option value="{{ $choreo->id }}" @selected($class->choreography_id == $choreo->id)>
                                    {{ $choreo->style }} ({{ $choreo->difficulty }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="border px-2 py-1">
                        <select name="status" class="w-full p-1 border rounded text-xs" required>
                            <option value="Upcoming" @selected($class->status === 'Upcoming')>Upcoming</option>
                            <option value="In Session" @selected($class->status === 'In Session')>In Session</option>
                            <option value="Completed" @selected($class->status === 'Completed')>Completed</option>
                            <option value="Cancelled" @selected($class->status === 'Cancelled')>Cancelled</option>
                        </select>
                    </td>
                    <td class="border px-2 py-1 space-y-1">
                        <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">💾 Simpan</button>
                </form>
                <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600">❌ Hapus</button>
                </form>
                    </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="border p-2 text-center text-white opacity-70">Tidak ada kelas lain.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
