{{-- components/sidebar-admin.blade.php --}}
<div class="p-4 space-y-4 text-sm font-medium">
    <div class="text-lg font-semibold mb-2 border-b border-white pb-2">Admin Dashboard</div>

    <ul class="space-y-2">
        <li>
            <a href="{{ route('admin.bookingRequests') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.bookingRequests') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Booking Requests
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reschedule_tickets') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.reschedule_tickets') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Reschedule Tickets
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.manage_classes') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.manage_classes') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Manage Classes
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.notifications') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.notifications') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Announcements
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.users.index') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Manage Users
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reports.analytics') }}"
               class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-800 transition 
               {{ request()->routeIs('admin.reports.analytics') ? 'bg-white text-black font-semibold' : 'text-white' }}">
                Reports & Analytics
                <span>&gt;</span>
            </a>
        </li>
    </ul>
</div>
