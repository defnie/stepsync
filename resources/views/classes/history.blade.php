@extends('layouts.app')

@section('title', 'Classes History')

@push('styles')
<style>
    body {
        background-image: url('/images/background2.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        color: white;
    }

    input[type="text"],
    input[type="url"] {
        background-color: rgba(255, 255, 255, 0.95);
        color: black;
    }

    .text-shadow {
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')

{{-- Filter Form --}}
<form method="GET" class="mb-6 p-4 rounded bg-white bg-opacity-10 backdrop-blur-md text-white flex flex-col md:flex-row gap-4 items-center">
    <div>
        <label for="month" class="block text-sm font-semibold mb-1">Month</label>
        <input type="month" name="month" id="month" value="{{ request('month') }}" class="p-2 rounded bg-white text-black">
    </div>

    <div>
        <label for="keyword" class="block text-sm font-semibold mb-1">Search by Title</label>
        <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" placeholder="e.g. Jazz, Beginner..." class="p-2 rounded bg-white text-black">
    </div>

    <div class="mt-4 md:mt-6">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            🔍 Filter
        </button>
        <a href="{{ route('instructor.history') }}" class="ml-2 underline text-sm text-white hover:text-blue-200">
            Reset
        </a>
    </div>
</form>

<h1 class="text-2xl font-bold mb-4 text-white text-shadow">📚 Class History & Documentation</h1>

@forelse ($classes as $class)
<div class="glass-card rounded p-6 mb-6">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-lg font-semibold">{{ $class->title }} ({{ $class->date }})</h2>
            <p><strong>Instructor:</strong> {{ $class->instructor->name }}</p>
            <p><strong>Attendance:</strong> {{ $class->attended_count }}/{{ $class->total_booked }}</p>
        </div>

        <div class="ml-4">
            <strong>Documentation:</strong>
            <ul class="list-disc ml-5">
                @if($class->doc_name1 && $class->doc_link1)
                    <li>
                        <a href="{{ $class->doc_link1 }}" target="_blank"
                           class="text-blue-300 underline hover:text-blue-500">
                            {{ $class->doc_name1 }}
                        </a>
                    </li>
                @endif
                @if($class->doc_name2 && $class->doc_link2)
                    <li>
                        <a href="{{ $class->doc_link2 }}" target="_blank"
                           class="text-blue-300 underline hover:text-blue-500">
                            {{ $class->doc_name2 }}
                        </a>
                    </li>
                @endif
                @if($class->doc_name3 && $class->doc_link3)
                    <li>
                        <a href="{{ $class->doc_link3 }}" target="_blank"
                           class="text-blue-300 underline hover:text-blue-500">
                            {{ $class->doc_name3 }}
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Toggle Form --}}
            <button
                type="button"
                onclick="document.getElementById('doc-form-{{ $class->id }}').classList.toggle('hidden')"
                class="bg-black text-white px-3 py-1 rounded hover:bg-gray-800 mt-2 text-sm"
            >
                📄 Add / Update Documentation
            </button>
        </div>
    </div>

    {{-- Hidden Form --}}
    <form
        id="doc-form-{{ $class->id }}"
        action="{{ route('instructor.history.updateDocs', $class->id) }}"
        method="POST"
        class="mt-4 space-y-2 hidden"
    >
        @csrf
        @method('PUT')

        <input type="text" name="doc_name1" value="{{ $class->doc_name1 }}" placeholder="Document Name 1"
               class="w-full p-2 rounded">
        <input type="url" name="doc_link1" value="{{ $class->doc_link1 }}" placeholder="Document Link 1 (https://...)"
               class="w-full p-2 rounded">

        <input type="text" name="doc_name2" value="{{ $class->doc_name2 }}" placeholder="Document Name 2"
               class="w-full p-2 rounded">
        <input type="url" name="doc_link2" value="{{ $class->doc_link2 }}" placeholder="Document Link 2 (https://...)"
               class="w-full p-2 rounded">

        <input type="text" name="doc_name3" value="{{ $class->doc_name3 }}" placeholder="Document Name 3"
               class="w-full p-2 rounded">
        <input type="url" name="doc_link3" value="{{ $class->doc_link3 }}" placeholder="Document Link 3 (https://...)"
               class="w-full p-2 rounded">

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            💾 Save Docs
        </button>
    </form>
</div>
@empty
<p class="text-white">🚫 No completed classes found.</p>
@endforelse
@endsection
