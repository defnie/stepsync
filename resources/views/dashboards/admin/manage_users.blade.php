@extends('layouts.app')

@section('title', '👥 Manage Users')

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
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    table input[type="checkbox"] {
        accent-color: #3b82f6;
        width: 16px;
        height: 16px;
    }
</style>
@endpush

@section('content')
<div class="relative min-h-screen bg-gray-900 text-white">
    <div class="absolute inset-0 z-0 bg-cover bg-center opacity-10" style="background-image: url('{{ asset('images/background2.png') }}');"></div>

    <div class="relative z-10 max-w-7xl mx-auto font-[Poppins] p-6">
        <h1 class="text-3xl font-bold mb-6">👥 Manage Users</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-600/20 border border-green-400 text-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto glass p-4 rounded-xl shadow">
            <table class="min-w-full border border-white border-opacity-20 text-sm text-white table-auto">
                <thead class="bg-white/10 text-white/80">
                    <tr>
                        <th class="p-3 border border-white/20">#</th>
                        <th class="p-3 border border-white/20">Name</th>
                        <th class="p-3 border border-white/20">Email</th>
                        <th class="p-3 border border-white/20">Roles</th>
                        <th class="p-3 border border-white/20 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr class="hover:bg-white/10 transition">
                            <td class="p-3 border border-white/10">{{ $index + 1 }}</td>
                            <td class="p-3 border border-white/10 font-medium">{{ $user->name }}</td>
                            <td class="p-3 border border-white/10 text-white/70">{{ $user->email }}</td>
                            <td class="p-3 border border-white/10">
                                <form method="POST" action="{{ route('admin.users.updateRoles', $user->id) }}" class="flex flex-wrap gap-3 items-center">
                                    @csrf
                                    @method('PUT')
                                    @foreach($roles as $role)
                                        <label class="flex items-center gap-1 text-xs text-white">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                @if($user->roles->contains('id', $role->id)) checked @endif>
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    @endforeach
                                    <button type="submit"
                                            class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-xs rounded">
                                        💾 Save
                                    </button>
                                </form>
                            </td>
                            <td class="p-3 border border-white/10 text-center">
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 text-xs">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
