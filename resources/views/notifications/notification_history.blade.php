@extends('layouts.app')

@section('title', '📨 Notifications History')

@section('content')
<div class="relative min-h-screen">
    <div class="absolute inset-0 z-0 bg-cover bg-center opacity-10" style="background-image: url('{{ asset('images/background1.png') }}');"></div>

    <div class="relative z-10 max-w-4xl mx-auto p-4 font-[Poppins]">
        <h1 class="text-3xl font-bold mb-6">📨 Notifications History</h1>

       @forelse ($notifications as $note)
        <div class="mb-4 p-4 rounded shadow 
            @if($note['type'] === 'Announcement') bg-blue-50 
            @elseif($note['type'] === 'Schedule Change') bg-yellow-50 
            @else bg-gray-100 @endif text-black">
            
            <h2 class="text-lg font-semibold text-black">{{ $note['title'] ?? 'Untitled' }}</h2>
            <p class="text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($note['date'])->format('d M Y, H:i') }}
            </p>
            <p class="mt-1 text-black">{{ $note['message'] ?? '' }}</p>
        </div>
    @empty
        <p class="text-gray-600">No notifications found.</p>
    @endforelse

    </div>
</div>
@endsection
