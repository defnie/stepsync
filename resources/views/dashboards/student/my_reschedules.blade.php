@extends('layouts.app')

@section('title', '🎫 My Reschedules')

@push('styles')
<style>
    .glass {
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen">

    {{-- Optional: dim background if needed --}}
    <div class="absolute inset-0 z-0 bg-cover bg-center opacity-10" style="background-image: url('{{ asset('images/background1.png') }}');"></div>

    <div class="relative z-10 max-w-5xl mx-auto p-6 font-[Poppins]">
        <h1 class="text-3xl font-bold mb-6 text-white">🎫 My Reschedule Tickets</h1>

        @if ($rescheduleTickets->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($rescheduleTickets as $ticket)
                    <div class="bg-white shadow-xl rounded-xl p-6 relative border-l-8 border-yellow-500 text-black">
                        <h3 class="text-lg font-bold mb-1">🔁 From: {{ $ticket->originalBooking->class->title }}</h3>
                        <p class="text-sm text-gray-700">📍 {{ $ticket->originalBooking->class->location }}</p>
                        <p class="text-sm text-gray-700">
                            🗓️ Original Date: {{ \Carbon\Carbon::parse($ticket->originalBooking->class->date)->format('d M Y') }}
                        </p>
                        <p class="text-sm text-gray-700">
                            ⌛ Expires At: <span class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($ticket->expires_at)->format('d M Y') }}</span>
                        </p>

                        <div class="mt-4">
                            @if (!$ticket->used)
                                <a href="{{ route('student.book_now') }}"
                                   class="inline-block bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500 text-sm font-medium">
                                    🎯 Use Now
                                </a>
                            @else
                                <span class="inline-block mt-2 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">
                                    ✅ Already Used
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-white/70 italic">You don't have any reschedule tickets yet.</p>
        @endif
    </div>

</div>
@endsection
