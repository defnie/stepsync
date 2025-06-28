@extends('layouts.app')

@section('title', 'Mark Attendance')

@push('styles')
<style>
    .glass {
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    table input[type="checkbox"] {
        transform: scale(1.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto font-[Poppins]">

    <div class="glass p-6 rounded-xl">
        <h1 class="text-3xl font-bold mb-6">📋 Mark Attendance for {{ $class->title }}</h1>

        <div class="mb-4 space-y-1">
            <p><strong>Date:</strong> {{ $class->date }}</p>
            <p><strong>Time:</strong> {{ $class->start_time }} - {{ $class->end_time }}</p>
            <p><strong>Location:</strong> {{ $class->location }}</p>
        </div>

        @if ($class->bookings->count())
        <form action="{{ route('attendance.class.save', $class->id) }}" method="POST">
            @csrf
            <div class="overflow-x-auto rounded">
                <table class="w-full table-auto border-collapse text-white text-sm">
                    <thead class="bg-white/10 text-white/80">
                        <tr>
                            <th class="border px-3 py-2 text-left">User</th>
                            <th class="border px-3 py-2 text-center">Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->bookings as $booking)
                        <tr class="hover:bg-white/5 transition">
                            <td class="border px-3 py-2">{{ $booking->user->name }}</td>
                            <td class="border px-3 py-2 text-center">
                                <input type="checkbox" name="attended_{{ $booking->user_id }}"
                                    {{ isset($existingAttendance[$booking->user_id]) && $existingAttendance[$booking->user_id] ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mt-4 text-sm font-semibold">
                💾 Save Attendance
            </button>
        </form>
        @else
        <p class="text-white/70 italic mt-4">No bookings found for this class.</p>
        @endif
    </div>
</div>
@endsection
