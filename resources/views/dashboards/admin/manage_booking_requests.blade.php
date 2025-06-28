@extends('layouts.app')

@section('title', 'Manage Booking Requests')

@push('styles')
<style>
    body {
        background-image: url('/images/background2.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
    }

    .glass {
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    table input,
    table select {
        background-color: rgba(255, 255, 255, 0.85);
        color: #000;
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen bg-gray-900 text-white">
    <div class="absolute inset-0 z-0 bg-cover bg-center opacity-70" style="background-image: url('{{ asset('images/background2.png') }}');"></div>

    <div class="relative z-10 max-w-5xl mx-auto p-6 font-[Poppins]">
        <h1 class="text-3xl font-bold mb-6">📋 Manage Booking Requests</h1>

        @if(session('success'))
            <div class="bg-green-600/20 border border-green-400 text-green-300 px-4 py-2 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="glass p-4 rounded-xl overflow-x-auto">
            <table class="w-full table-auto border-collapse text-white text-sm">
                <thead class="bg-white/10 text-white/80">
                    <tr>
                        <th class="border px-3 py-2">User</th>
                        <th class="border px-3 py-2">Class</th>
                        <th class="border px-3 py-2">Payment</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr class="hover:bg-white/10 transition">
                            <td class="border px-3 py-2">{{ $req->user->name }}</td>
                            <td class="border px-3 py-2">{{ $req->class->title }}</td>
                            <td class="border px-3 py-2">{{ $req->payment_type }}</td>
                            <td class="border px-3 py-2">{{ $req->status }}</td>
                            <td class="border px-3 py-2 space-x-2">
                                @if($req->status === 'Pending')
                                    <form class="inline" method="POST" action="{{ route('admin.bookingRequests.updateStatus', ['id' => $req->id, 'status' => 'Confirmed']) }}">
                                        @csrf
                                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">✅ Approve</button>
                                    </form>
                                    <form class="inline" method="POST" action="{{ route('admin.bookingRequests.updateStatus', ['id' => $req->id, 'status' => 'Denied']) }}">
                                        @csrf
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">❌ Deny</button>
                                    </form>
                                @else
                                    <span class="text-white/60 text-xs">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-white/60 py-4">No booking requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
