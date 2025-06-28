@extends('layouts.app')

@section('title', '📢 Manage Announcements')

@section('content')
    <style>
        .glass {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>

    <div class="relative min-h-screen bg-gray-900 text-white font-[Poppins]">
        <div class="absolute inset-0 z-0 bg-cover bg-center opacity-60" style="background-image: url('{{ asset('images/background1.png') }}');"></div>

        <div class="relative z-10 max-w-4xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">📢 Manage Announcements</h1>

            @if(session('success'))
                <div class="bg-green-600/20 border border-green-400 text-green-300 px-4 py-2 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ========== Create Announcement ========== --}}
            <div class="glass p-6 rounded-xl shadow mb-10">
                <h2 class="text-xl font-semibold mb-4">📝 Create New Announcement</h2>
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <input type="text" name="title" placeholder="Title"
                           class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black placeholder-gray-700 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

                    <textarea name="content" placeholder="Content" rows="3"
                              class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black placeholder-gray-700 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>

                    <select name="target_role"
                            class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">🎯 Select Target Role</option>
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                        <option value="admin">Admin</option>
                    </select>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                        ➕ Post Announcement
                    </button>
                </form>
            </div>

            {{-- ========== Existing Announcements ========== --}}
            <h2 class="text-xl font-semibold mb-4">📚 Existing Announcements</h2>

            @foreach($announcements as $announcement)
                <div class="glass p-6 rounded-xl shadow mb-6">
                    <form action="{{ route('admin.notifications.update', $announcement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="text" name="title" value="{{ $announcement->title }}"
                               class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black placeholder-gray-700 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

                        <textarea name="content" rows="3"
                                  class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black placeholder-gray-700 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-400" required>{{ $announcement->content }}</textarea>

                        <select name="target_role"
                                class="w-full p-3 rounded-lg bg-white/70 border border-white/30 text-black mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            <option value="student" @selected($announcement->target_role === 'student')>Student</option>
                            <option value="instructor" @selected($announcement->target_role === 'instructor')>Instructor</option>
                            <option value="admin" @selected($announcement->target_role === 'admin')>Admin</option>
                        </select>

                        <div class="flex gap-3">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                                💾 Update
                            </button>
                    </form>
                    <form action="{{ route('admin.notifications.destroy', $announcement->id) }}" method="POST"
                          onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
                            🗑️ Delete
                        </button>
                    </form>
                        </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
