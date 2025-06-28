@extends('layouts.app')

@section('title', '📅 Book a Class')

@section('content')
<h1 class="text-3xl font-bold mb-4 text-white">📅 Book a Class</h1>

<div class="max-w-6xl mx-auto w-full">

    {{-- Month navigation --}}
    <div class="flex justify-between items-center mb-2 text-white">
        @php
            $prevMonth = $currentMonth->copy()->subMonthNoOverflow()->format('Y-m');
            $nextMonth = $currentMonth->copy()->addMonthNoOverflow()->format('Y-m');
        @endphp
        <button onclick="location.href='?month={{ $prevMonth }}'" class="text-xl">&lt;</button>
        <span class="text-lg font-bold">{{ $currentMonth->format('F Y') }}</span>
        <button onclick="location.href='?month={{ $nextMonth }}'" class="text-xl">&gt;</button>
    </div>

    {{-- Day names --}}
    <div class="grid grid-cols-7 gap-1 text-center text-white">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
            <div class="font-bold">{{ $dayName }}</div>
        @endforeach
    </div>

    {{-- Calendar grid --}}
    <div class="overflow-auto">
        <div class="grid grid-cols-7 gap-1 bg-black text-white p-2 rounded min-w-[700px]">
            @foreach ($calendarDays as $day)
                <div class="border border-white min-h-[100px] p-1 relative">
                    <div class="text-xs">{{ $day['date']->format('j M') }}</div>

                    @foreach ($day['classes'] as $class)
                        <div class="mt-1 bg-gray-800 p-2 rounded text-xs">
                            <strong>{{ $class->title }}</strong><br>
                            👤 {{ $class->instructor->name }}<br>
                            📍 {{ $class->location }}<br>
                            🕒 {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}<br>
                            💃 {{ $class->choreography->style }} ({{ $class->choreography->difficulty }})<br>
                            💰 HTM: 65k<br>
                            🎟️ <span class="text-base font-semibold">Slots: {{ $class->bookings->count() }} / {{ $class->max_slot }}</span><br>

                            @php
                                $youtubeId = null;
                                $video = $class->videos->first();
                                if ($video && preg_match('/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video->video_url, $matches)) {
                                    $youtubeId = $matches[1];
                                }
                            @endphp

                            @if ($youtubeId)
                                <iframe width="135" height="135" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen class="rounded mt-1"></iframe>
                            @else
                                <p class="text-xs text-red-500">Link video tidak valid</p>
                            @endif

                            <button onclick="showBookingModal({{ $class->id }}, '{{ $class->title }}', '{{ $day['date']->format('j M Y') }}', '{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}')" class="bg-green-500 hover:bg-green-700 text-white rounded px-2 py-1 mt-1 text-xs">Book Now</button>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Booking Modal --}}
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center items-center z-50">
    <div class="bg-white text-black p-6 rounded shadow w-80">
        <h2 class="font-bold mb-2 text-lg">Choose Payment Type</h2>
        <p id="classInfo" class="text-sm mb-4"></p>
        <form id="bookingForm" method="POST">
            @csrf
            <input type="hidden" name="payment_type" id="payment_type">
            <button type="button" onclick="submitBooking('Transfer')" class="bg-blue-600 text-white px-3 py-1 rounded mr-2">Transfer</button>
            <button type="button" onclick="submitBooking('Reschedule')" class="bg-yellow-500 text-white px-3 py-1 rounded">Use Reschedule Ticket</button>
            <button type="button" onclick="closeBookingModal()" class="mt-2 text-sm underline text-gray-500">Cancel</button>
        </form>
    </div>
</div>

{{-- Error Modal --}}
<div id="rescheduleErrorModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center items-center z-50">
    <div class="bg-white text-black p-6 rounded shadow w-80">
        <h2 class="font-bold text-lg mb-2 text-red-600">Reschedule Unavailable</h2>
        <p class="text-sm mb-4">You don’t have a valid reschedule ticket for this class.</p>
        <button onclick="closeErrorModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Close</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
let bookingClassId = null;

function showBookingModal(classId, title, date, time) {
    bookingClassId = classId;
    document.getElementById('classInfo').innerHTML = `
        <span class="block font-semibold">${title}</span>
        <span class="block">${date} at ${time}</span>
        <span class="block mt-1 text-green-700 font-bold">💰 HTM: 65k</span>
    `;
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function submitBooking(type) {
    document.getElementById('payment_type').value = type;

    const form = document.getElementById('bookingForm');
    const actionUrl = `{{ route('student.bookings.bookNow', ':id') }}`.replace(':id', bookingClassId);
    form.action = actionUrl;

    if (type === 'Reschedule') {
        fetch(`{{ route('student.checkReschedule', ':id') }}`.replace(':id', bookingClassId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.eligible) {
                showErrorModal(data.message);
            } else {
                disableAndSubmit(form);
            }
        });
    } else {
        disableAndSubmit(form);
    }
}

function disableAndSubmit(form) {
    Array.from(form.querySelectorAll('button')).forEach(btn => {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    document.getElementById('classInfo').innerText += ' (Processing...)';
    form.submit();
}

function showErrorModal(message) {
    const modal = document.getElementById('rescheduleErrorModal');
    modal.querySelector('p').innerText = message;
    modal.classList.remove('hidden');
}

function closeErrorModal() {
    document.getElementById('rescheduleErrorModal').classList.add('hidden');
}
</script>
@endpush
