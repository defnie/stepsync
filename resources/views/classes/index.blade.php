@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
<h1 class="text-3xl font-bold mb-6 font-[Poppins]">Manajemen Kelas</h1>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

@if (!empty($classesInSession) && $classesInSession->count()) @endif

{{-- =================== CURRENT CLASS IN SESSION =================== --}}
@if ($classesInSession->count())
    <div class="mb-6 font-[Poppins]">
        <h2 class="text-xl font-semibold mb-2">🔥 Kelas Sedang Berlangsung</h2>
        <div class="flex flex-col md:flex-row gap-4">
           @foreach ($classesInSession as $class)
                <div class="w-full bg-white shadow-lg rounded-xl p-6 flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold">{{ $class->title }}</h3>
                        <p class="text-sm">👤 {{ $class->instructor->name }}</p>
                        <p class="text-sm">📍 {{ $class->location }}</p>
                        <p class="text-sm">
                            🗓️ {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
                            🕐 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                        </p>
                        <span class="inline-block mt-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Sedang Berlangsung</span>
                    </div>

                    {{-- YouTube embed --}}
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
    <div class="mb-6 font-[Poppins] p-4 bg-yellow-50 rounded">
        🚫 Tidak ada kelas yang sedang berlangsung.
    </div>
@endif

{{-- =================== FORM TO ADD NEW CLASS =================== --}}
<div class="bg-white p-6 rounded-xl shadow mb-6 font-[Poppins]">
    <h2 class="text-xl font-semibold mb-4">➕ Tambah Kelas Baru</h2>
    <form action="{{ route('classes.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="title" placeholder="Judul Kelas" class="p-2 border rounded" required>
            <input type="text" name="location" placeholder="Lokasi" class="p-2 border rounded" required>

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

{{-- =================== OTHER CLASSES =================== --}}
<h2 class="text-xl font-semibold mb-2 font-[Poppins]">📌 Kelas Lainnya</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-[Poppins]">
    @foreach ($otherClasses as $class)
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-bold">{{ $class->title }}</h3>
                <p class="text-sm">👤 {{ $class->instructor->name }}</p>
                <p class="text-sm">📍 {{ $class->location }}</p>
                <p class="text-sm">
                    🗓️ {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
                    🕐 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                </p>

                @php
                    $now = \Carbon\Carbon::now();
                    $start = \Carbon\Carbon::parse("{$class->date} {$class->start_time}");
                    $end = \Carbon\Carbon::parse("{$class->date} {$class->end_time}");
                @endphp

                @if ($now->lt($start))
                    <span class="inline-block mt-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Belum Mulai</span>
                @elseif ($now->between($start, $end))
                    <span class="inline-block mt-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Sedang Berlangsung</span>
                @else
                    <span class="inline-block mt-2 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Selesai</span>
                @endif

                {{-- === Action buttons === --}}
                <div class="mt-3 flex gap-2">
                <details class="mt-2">
                    <summary class="cursor-pointer text-blue-600 hover:underline">✏️ Edit Kelas</summary>
                    <form action="{{ route('classes.update', $class->id) }}" method="POST" class="mt-2 space-y-2">
                        @csrf
                        @method('PUT')

                        <input type="text" name="title" value="{{ $class->title }}" class="w-full p-2 border rounded" required>

                        <input type="text" name="location" value="{{ $class->location }}" class="w-full p-2 border rounded" required>

                        <input type="date" name="date" value="{{ $class->date }}" class="w-full p-2 border rounded" required>

                        <input type="time" name="start_time" value="{{ $class->start_time }}" class="w-full p-2 border rounded" required>

                        <input type="time" name="end_time" value="{{ $class->end_time }}" class="w-full p-2 border rounded" required>

                        <input type="number" name="max_slot" value="{{ $class->max_slot }}" class="w-full p-2 border rounded" required>

                        <select name="choreography_id" class="w-full p-2 border rounded" required>
                            @foreach($choreographies as $choreo)
                                <option value="{{ $choreo->id }}" @selected($class->choreography_id == $choreo->id)>
                                    {{ $choreo->style }} ({{ $choreo->difficulty }})
                                </option>
                            @endforeach
                        </select>

                        <input 
                            type="url" 
                            name="video_url" 
                            value="{{ optional($class->videos->first())->video_url }}" 
                            class="w-full p-2 border rounded" 
                            placeholder="Link YouTube (https://...)" 
                            required
                        >


                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                    </form>
                </details>


                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan kelas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                            ❌ Batal
                        </button>
                    </form>
                </div>
            </div>

            {{-- YouTube embed --}}
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


@endsection
