@extends('layouts.app')

@section('title', 'Manage Reschedule Tickets')

@section('content')
    <style>
        .glass {
            background-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        th, td {
            border-color: rgba(255, 255, 255, 0.2);
        }
    </style>

    <div class="relative min-h-screen bg-gray-900 text-white font-[Poppins]">
        <div class="absolute inset-0 bg-cover bg-center opacity-70 z-0"
             style="background-image: url('{{ asset('images/background1.png') }}');"></div>

        <div class="relative z-10 max-w-6xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">🎟 Manage Reschedule Tickets</h1>

            @if(session('success'))
                <div class="bg-green-600/20 border border-green-400 text-green-300 px-4 py-2 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $grouped = $tickets->groupBy('user_id');
            @endphp

            @forelse($grouped as $userId => $userTickets)
                @php $user = $userTickets->first()->user; @endphp

                <div class="glass rounded-xl p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $user->name }} 
                        <span class="text-sm text-white/60">({{ $userTickets->count() }} tickets)</span>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left table-auto border-collapse text-white">
                            <thead class="bg-white/10 text-white/80">
                                <tr>
                                    <th class="border px-4 py-2">Original Class</th>
                                    <th class="border px-4 py-2">Expiry Date</th>
                                    <th class="border px-4 py-2">Used</th>
                                    <th class="border px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userTickets as $ticket)
                                    <tr class="hover:bg-white/10 transition">
                                        <td class="border px-4 py-2">
                                            {{ $ticket->originalBooking->class->name ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            {{ \Carbon\Carbon::parse($ticket->expires_at)->format('d M Y') }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            {{ $ticket->used ? '✅ Used' : '❌ Not Used' }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            @if(!$ticket->used)
                                                <form action="{{ route('admin.reschedule_tickets.use', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                                        Use Ticket
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-white/50">Already used</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @empty
                <p class="text-white/60">No reschedule tickets found.</p>
            @endforelse

            {{-- Delete Expired Tickets --}}
            <form action="{{ route('admin.reschedule_tickets.delete_expired') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded shadow-md">
                    🗑️ Delete Expired Tickets
                </button>
            </form>
        </div>
    </div>
@endsection
