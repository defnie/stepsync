@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6; /* Light gray background for better contrast */
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
        background-color: #ffffff;
        color: #1f2937; /* Gray-800 for better visibility */
        border: 1px solid #d1d5db; /* Light border */
        padding: 0.5rem;
    }

    input:focus {
        outline: none;
        border-color: #6366f1; /* Indigo */
        box-shadow: 0 0 0 1px #6366f1;
    }

    label {
        color: #374151; /* Gray-700 */
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto p-6 mt-10 bg-white rounded shadow font-[Poppins]">
    <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

    @if (session('status') === 'profile-updated')
        <div class="mb-4 text-green-600 font-medium">Profile updated successfully.</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="mt-1 block w-full rounded-md shadow-sm" required>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="mt-1 block w-full rounded-md shadow-sm" required>
        </div>

        <div>
            <label for="profile_picture" class="block text-sm font-medium">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture"
                   class="mt-1 block w-full rounded-md shadow-sm">

            @if ($user->profile_picture)
                <img src="{{ $user->profile_picture }}" alt="Current Profile Picture" class="mt-2 w-20 h-20 rounded-full object-cover">
            @endif
        </div>

        <div class="pt-4">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-semibold">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
