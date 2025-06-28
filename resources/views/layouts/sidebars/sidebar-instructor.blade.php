{{-- components/sidebar-instructor.blade.php --}}
@php
    $activeRoute = request()->route()?->getName();
@endphp

<div class="p-4 space-y-4 text-sm font-medium">
    <div class="text-lg font-semibold mb-2 border-b border-white pb-2">Instructor Dashboard</div>

    <ul class="space-y-2">
        <li>
            <a href="{{ route('instructor.classes.index') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'instructor.classes.index' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Manajemen Kelas
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('instructor.attendance.selector') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'instructor.attendance.selector' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Mark Attendance
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('instructor.history') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'instructor.history' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Class History & Docs
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('instructor.analytics') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'instructor.analytics' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Attendance Analytics
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('notifications.announcements') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'notifications.announcements' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Make an Annoucement
                <span>&gt;</span>
            </a>
        </li>

        <li>
            <a href="{{ route('notifications.history') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ $activeRoute === 'notifications.history' ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Notification History
                <span>&gt;</span>
            </a>
        </li>
    </ul>
</div>
