@extends('layouts.app')

@section('title', 'Pilih Kelas untuk Absensi')

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

    .progress-bar {
        background-color: rgba(0, 0, 0, 0.8);
    }

    .text-shadow {
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')
<h1 class="text-2xl font-bold mb-4 text-white text-shadow">📋 Pilih Kelas untuk Mark Attendance</h1>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($classes->count())
    <div class="grid gap-4">
        @foreach($classes as $class)
            <div class="glass-card rounded-lg shadow p-6 text-white">
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $class->title }}</h2>
                        <p class="text-sm opacity-90">
                            🗓️ {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }} |
                            🕒 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                        </p>
                        <p class="text-sm opacity-90">📍 {{ $class->location }}</p>
                    </div>
                    <a href="{{ route('instructor.attendance', $class->id) }}"
                       class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 text-sm">
                        ✅ Mark Attendance
                    </a>
                </div>

                <p class="text-sm">
                    👥 Attendance: 
                    <span class="font-medium text-green-300">{{ $class->attended_count }}</span> / 
                    <span class="font-medium text-white">{{ $class->bookings_count }}</span> present 
                    <span class="text-red-300">({{ $class->bookings_count - $class->attended_count }} absent)</span>
                </p>

                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="progress-bar h-2 rounded-full" style="width: {{ $class->bookings_count > 0 ? ($class->attended_count / $class->bookings_count) * 100 : 0 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-200">🚫 Tidak ada kelas tersedia untuk absensi.</p>
@endif
@endsection
