@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
@endpush

<nav class="bg-black border-b-2 border-white p-4 shadow flex justify-between items-center">
    <!-- Left side (logo or empty) -->
    <div></div>

    <!-- Right side -->
    <div class="flex items-center h-16 space-x-3">
        <!-- Notification bell -->
        <div class="relative">
            <button id="notificationBell" class="relative text-white hover:opacity-80 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span id="notificationDot" class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white text-black rounded-md shadow-lg z-50">
                <div class="p-2 border-b font-semibold text-sm">Notifications</div>
                <div class="max-h-64 overflow-y-auto">
                   @php
                    use App\Models\Announcement;
                    use App\Models\ScheduleChangeNotification;
                    use Carbon\Carbon;

                    $userRole = session('active_role') ?? Auth::user()->roles->first()->name;

                    $announcements = Announcement::where('target_role', $userRole)
                        ->orWhereNull('target_role')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'type' => 'announcement',
                                'title' => $item->title,
                                'message' => $item->content,
                                'created_at' => $item->created_at
                            ];
                        });

                   $scheduleChanges = ScheduleChangeNotification::with('class')
                    ->get()
                    ->map(function ($item) {
                        $oldDate = Carbon::parse($item->sent_at)->toDateString(); // date before change (approx)
                        $newDate = $item->class->date;

                        $oldTime = Carbon::parse($item->old_time)->format('H:i');
                        $newTime = Carbon::parse($item->new_time)->format('H:i');

                        $message = "Class '{$item->class->title}' was rescheduled";

                        if ($oldDate !== $newDate && $oldTime !== $newTime) {
                            $message .= " from $oldTime on $oldDate to $newTime on $newDate";
                        } elseif ($oldTime !== $newTime) {
                            $message .= " from $oldTime to $newTime on $newDate";
                        } elseif ($oldDate !== $newDate) {
                            $message .= " to a new date: $newDate at $newTime";
                        } else {
                            $message .= " (schedule updated)";
                        }

                        return [
                            'type' => 'schedule_change',
                            'title' => 'Schedule Changed',
                            'message' => $message,
                            'created_at' => $item->created_at
                        ];
                    });


                    $notifications = collect($announcements)
                        ->merge($scheduleChanges)
                        ->sortByDesc('created_at')
                        ->take(10);
                @endphp

                @forelse ($notifications as $note)
                    <div class="px-4 py-2 text-sm hover:bg-gray-100">
                        <div class="font-medium">{{ $note['title'] }}</div>
                        <div class="text-xs text-gray-500">{{ $note['created_at']->format('Y-m-d H:i') }}</div>
                        <div class="text-xs mt-1">{{ $note['message'] }}</div>
                    </div>
                @empty
                    <div class="px-4 py-2 text-sm text-gray-500">No notifications</div>
                @endforelse

                </div>
            </div>
        </div>

        <!-- Divider line -->
        <div class="relative h-16">
            <div class="absolute top-0 bottom-0 left-0 w-px bg-white"></div>
        </div>

        <!-- Profile -->
        @auth
        <div class="relative">
            <button id="profileDropdown" class="flex items-center space-x-2 text-white hover:opacity-80 transition">
                <img src="{{ Auth::user()->profile_picture ?? 'https://via.placeholder.com/40' }}"
                     class="w-9 h-9 rounded-full object-cover border-2 border-white" alt="Profile">

                <div class="text-left">
                    <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-xs opacity-80">
                        {{ session('active_role') ?? Auth::user()->roles->first()->name }}
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-52 bg-white text-black rounded-md shadow-lg z-50">
                @php
                    $role = session('active_role') ?? Auth::user()->roles->first()->name;
                    $dashboardRoute = match ($role) {
                        'admin' => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        'student' => route('student.book_now'),
                        default => '#',
                    };
                @endphp

                <a href="{{ $dashboardRoute }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                    Go to {{ ucfirst($role) }} Dashboard
                </a>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Edit Profile</a>

                @php
                    $roles = Auth::user()->roles->pluck('name');
                    $currentRole = session('active_role');
                @endphp

                @foreach ($roles as $roleName)
                    @if ($roleName !== $currentRole)
                        <form method="POST" action="{{ route('switch.role') }}">
                            @csrf
                            <input type="hidden" name="role" value="{{ $roleName }}">
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                Log in as {{ ucfirst($roleName) }} instead
                            </button>
                        </form>
                    @endif
                @endforeach

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>

    <script>
        document.getElementById('notificationBell').addEventListener('click', function () {
            document.getElementById('notificationDropdown').classList.toggle('hidden');
        });

        document.getElementById('profileDropdown').addEventListener('click', function () {
            document.getElementById('profileDropdownMenu').classList.toggle('hidden');
        });
    </script>
</nav>
