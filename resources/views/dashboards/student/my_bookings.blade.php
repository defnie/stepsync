@extends('layouts.app')

@section('title', '🎟️ My Bookings')

@section('content')
    <style>
        .glass {
            background-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-border {
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>

    <div class="relative min-h-screen bg-gray-900 text-white font-[Poppins]">

        {{-- Background --}}
        <div class="absolute inset-0 z-0 bg-cover bg-center opacity-75"
             style="background-image: url('{{ asset('images/background1.png') }}');">
        </div>

        <div class="relative z-10 max-w-6xl mx-auto p-6">

            <h1 class="text-4xl font-bold mb-8">🎟️ My Bookings</h1>

            {{-- Pending Booking Requests --}}
            <h2 class="text-2xl font-semibold mb-4">⏳ Pending Booking Requests</h2>

            @if ($pendingRequests->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    @foreach ($pendingRequests as $request)
                        <div class="glass rounded-xl p-6 glass-border">
                            <h3 class="text-xl font-semibold">{{ $request->class->title }}</h3>
                            <p class="text-sm text-white/80">📍 {{ $request->class->location }}</p>
                            <p class="text-sm text-white/80">
                                🗓️ {{ \Carbon\Carbon::parse($request->class->date)->format('d M Y') }} |
                                🕒 {{ \Carbon\Carbon::parse($request->class->start_time)->format('H:i') }}
                            </p>
                            <p class="text-sm text-white/80">💰 Payment: {{ $request->payment_type }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs bg-yellow-300 text-yellow-900 rounded">
                                Pending
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-300 mb-10">You have no pending booking requests.</p>
            @endif

            {{-- Filter Bar --}}
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="text-sm font-semibold">Filter:</span>
                <a href="{{ route('student.myBookings') }}"
                   class="px-3 py-1 rounded text-sm border {{ is_null($status) ? 'bg-white text-black' : 'border-white text-white hover:bg-white hover:text-black' }}">
                    All
                </a>
                @foreach ($validStatuses as $s)
                    <a href="{{ route('student.myBookings', ['status' => $s]) }}"
                       class="px-3 py-1 rounded text-sm border {{ $status === $s ? 'bg-white text-black' : 'border-white text-white hover:bg-white hover:text-black' }}">
                        {{ ucfirst($s) }}
                    </a>
                @endforeach
            </div>

            {{-- Filtered Bookings --}}
            <h2 class="text-2xl font-semibold mb-4">📚 My Classes</h2>

            @if ($filteredBookings->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($filteredBookings as $booking)
                        <div class="glass rounded-xl p-6 glass-border">
                            <h3 class="text-xl font-semibold">{{ $booking->class->title }}</h3>
                            <p class="text-sm text-white/80">📍 {{ $booking->class->location }}</p>
                            <p class="text-sm text-white/80">
                                🗓️ {{ \Carbon\Carbon::parse($booking->class->date)->format('d M Y') }} |
                                🕒 {{ \Carbon\Carbon::parse($booking->class->start_time)->format('H:i') }}
                            </p>
                            <p class="text-sm text-white/80">💰 Payment: {{ $booking->payment_type }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs bg-white text-black rounded">
                                {{ ucfirst($booking->status) }}
                            </span>

                            {{-- Reschedule Option --}}
                            @php
                                $classDateTime = \Carbon\Carbon::parse($booking->class->date . ' ' . $booking->class->start_time);
                                $eligible = now()->lt($classDateTime->copy()->subHours(24));
                                $alreadyRequested = $booking->rescheduleTickets->where('used', false)->count();
                            @endphp

                            <div class="mt-3">
                                @if ($eligible && !$alreadyRequested)
                                    <form method="POST" action="{{ route('student.requestReschedule', $booking->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-sm mt-1">
                                            🔁 Request Reschedule
                                        </button>
                                    </form>
                                @elseif ($alreadyRequested)
                                    <span class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                        Reschedule Ticket Requested
                                    </span>
                                @else
                                    <span class="inline-block mt-2 px-2 py-1 text-xs bg-gray-600 text-gray-300 rounded">
                                        Reschedule Unavailable
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-300">No bookings found{{ $status ? " with status '$status'" : '' }}.</p>
            @endif

        </div>
    </div>
@endsection
