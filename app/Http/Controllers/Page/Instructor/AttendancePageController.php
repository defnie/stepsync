<?php

namespace App\Http\Controllers\Page\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\Choreography;
use App\Models\AttendanceReport;
use Carbon\Carbon;


class AttendancePageController extends Controller
{
   public function attendanceSelector()
    {
        $classes = ClassModel::withCount(['bookings'])->get();

        // Add attended count manually
        foreach ($classes as $class) {
            $class->attended_count = $class->attendanceReports()->where('attended', true)->count();
        }

        return view('classes.attendance-selector', compact('classes'));
    }



public function attendance($id)
    {
        $class = ClassModel::with('bookings.user')->findOrFail($id);
        $existingAttendance = AttendanceReport::where('class_id', $id)->pluck('attended', 'user_id')->toArray();

        return view('classes.attendance', compact('class', 'existingAttendance'));
    }

public function saveAttendance(Request $request, $id)
    {
        $class = ClassModel::with('bookings')->findOrFail($id);

        foreach ($class->bookings as $booking) {
            AttendanceReport::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'user_id' => $booking->user_id,
                ],
                [
                    'instructor_id' => auth()->id(),
                    'attended' => $request->has("attended_{$booking->user_id}"),
                ]
            );
        }

        return redirect()->route('instructor.attendance.selector', $class->id)
                    ->with('success', 'Attendance saved successfully!');
    }


}
