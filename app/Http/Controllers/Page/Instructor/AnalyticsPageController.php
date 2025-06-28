<?php

namespace App\Http\Controllers\Page\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\Choreography;
use App\Models\AttendanceReport;
use Carbon\Carbon;


class AnalyticsPageController extends Controller
{
   public function index()
{
    // Calculate attendance percentage per class
    $classes = ClassModel::withCount('attendances')->get();

    foreach ($classes as $class) {
        $maxSlot = $class->max_slot ?: 1; // Avoid division by zero
        $class->attendance_percent = round(($class->attendances_count / $maxSlot) * 100, 1);
    }

    // Get most active students
    $activeStudents = AttendanceReport::with('user')
        ->selectRaw('user_id, count(*) as attendance_count')
        ->groupBy('user_id')
        ->orderByDesc('attendance_count')
        ->limit(5)
        ->get();

    return view('reports.attendance-analytics', compact('classes', 'activeStudents'));
}
    // public function history()
    // {
    //     $classes = ClassModel::where('status', 'Completed')
    //         ->with(['instructor'])
    //         ->withCount(['bookings as total_booked'])
    //         ->get();

    //     foreach ($classes as $class) {
    //         $class->attended_count = AttendanceReport::where('class_id', $class->id)
    //             ->where('attended', true)
    //             ->count();
    //     }

    //     return view('classes.history', compact('classes'));
    // }

    public function attendanceAnalytics(Request $request)
    {
        // Apply filters
        $query = ClassModel::query()->with(['choreography', 'instructor', 'attendanceReports']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->choreography_id) {
            $query->where('choreography_id', $request->choreography_id);
        }

        if ($request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $classes = $query->get();
        // Calculate attendance % per class
        $classes->each(function ($class) {
            $totalBooked = $class->bookings()->count();
            $attended = $class->attendanceReports()->where('attended', true)->count();
            $class->attendance_percent = $totalBooked > 0 ? round(($attended / $totalBooked) * 100, 2) : 0;
        });

        // Most active students
        $activeStudents = \App\Models\AttendanceReport::select('user_id')
            ->where('attended', true)
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                $q->whereHas('class', function ($classQuery) use ($request) {
                    $classQuery->whereBetween('date', [$request->start_date, $request->end_date]);
                });
            })
            ->groupBy('user_id')
            ->selectRaw('count(*) as attendance_count, user_id')
            ->orderByDesc('attendance_count')
            ->with('user')
            ->get();

        $choreographies = \App\Models\Choreography::all();
        $instructors = \App\Models\User::all();

        return view('reports.attendance-analytics', compact('classes', 'activeStudents', 'choreographies', 'instructors'));
    }
}
