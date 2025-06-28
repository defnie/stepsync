@php
    $activeRoute = request()->route()?->getName() ?? 'student.book_now';
@endphp

<div class="min-h-screen w-64 bg-black border-r border-white text-white p-4 font-[Poppins]">
    <!-- Dashboard Header (not a link) -->
    <div class="flex items-center space-x-3 mb-4">
        <h2 class="text-lg font-semibold">STUDENT DASHBOARD</h2>
    </div>

    <!-- Separator -->
    <hr class="border-white mb-4">

    <!-- Navigation Links -->
    <ul class="space-y-2">
        <li>
            <a href="{{ route('student.book_now') }}"
               class="flex justify-between items-center p-2 rounded hover:bg-white hover:text-black transition
                      {{ $activeRoute === 'student.book_now' ? 'bg-white text-black font-semibold' : '' }}">
                Book Now
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.myBookings') }}"
               class="flex justify-between items-center p-2 rounded hover:bg-white hover:text-black transition
                      {{ $activeRoute === 'student.myBookings' ? 'bg-white text-black font-semibold' : '' }}">
                My Classes
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.my_reschedules') }}"
               class="flex justify-between items-center p-2 rounded hover:bg-white hover:text-black transition
                      {{ $activeRoute === 'student.my_reschedules' ? 'bg-white text-black font-semibold' : '' }}">
                Reschedule Ticket
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('notifications.history') }}"
               class="flex justify-between items-center p-2 rounded hover:bg-white hover:text-black transition
                      {{ $activeRoute === 'student.notifications.history' ? 'bg-white text-black font-semibold' : '' }}">
                Announcement History
                <span>&gt;</span>
            </a>
        </li>
    </ul>
</div>
