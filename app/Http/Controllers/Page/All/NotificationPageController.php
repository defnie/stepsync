<?php

namespace App\Http\Controllers\Page\All;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Booking;
use App\Models\ScheduleChangeNotification;

class NotificationPageController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    public function history()
{
    $user = auth()->user();
    $role = $user->roles->pluck('name')->toArray(); // assuming many-to-many

    // Fetch announcements targeted to the user's role or to all
    $announcements = \App\Models\Announcement::where(function ($query) use ($role) {
        $query->whereNull('target_role')
              ->orWhereIn('target_role', $role);
    })->get()->map(function ($a) {
        return [
            'type' => 'Announcement',
            'title' => $a->title,
            'message' => $a->content,
            'date' => $a->created_at,
        ];
    });

    // Fetch schedule changes for any class the user is related to
    $classIds = \App\Models\Booking::where('user_id', $user->id)->pluck('class_id');

    $scheduleChanges = \App\Models\ScheduleChangeNotification::whereIn('class_id', $classIds)->get()->map(function ($s) {
        return [
            'type' => 'Schedule Change',
            'title' => 'Jadwal Diubah',
            'message' => "Jadwal kelas diubah dari {$s->old_time} ke {$s->new_time}",
            'date' => $s->created_at,
        ];
    });

    // Combine & sort by date
$notifications = collect([...$announcements, ...$scheduleChanges])
    ->sortByDesc('date')
    ->values();


    return view('notifications.notification_history', compact('notifications'));
}
}
