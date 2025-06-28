@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
    <style>
        .glass {
            background-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>

    <div class="relative min-h-screen bg-gray-900 text-white font-[Poppins]">
        {{-- Background --}}
        <div class="absolute inset-0 z-0 bg-cover bg-center opacity-70"
             style="background-image: url('{{ asset('images/background1.png') }}');"></div>

        <div class="relative z-10 max-w-4xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">📢 Announcements</h1>

            @if(session('success'))
                <div class="bg-green-600/20 border border-green-400 text-green-300 px-4 py-2 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('notifications.announcements.store') }}" method="POST" class="glass space-y-4 p-6 rounded-xl mb-10 shadow-md">
                @csrf
                <input type="text" name="title" placeholder="Title"
                       class="w-full p-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>

                <textarea name="content" rows="4" placeholder="Content"
                          class="w-full p-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400"
                          required></textarea>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                    📤 Post Announcement
                </button>
            </form>

            {{-- List --}}
            <div class="grid gap-6">
                @forelse($announcements as $announcement)
                    <div class="glass rounded-xl p-6 shadow">
                        <h2 class="text-xl font-semibold text-white">{{ $announcement->title }}</h2>
                        <p class="text-white/80 mt-2">{{ $announcement->content }}</p>

                        @if($announcement->target_role)
                            <p class="text-sm text-white/50 mt-2">🎯 Target: {{ ucfirst($announcement->target_role) }}</p>
                        @endif
                        <p class="text-sm text-white/40">🕒 {{ $announcement->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                @empty
                    <p class="text-white/60">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
