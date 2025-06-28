<?php

namespace App\Http\Controllers\Page\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\BookingRequest;
use App\Models\Booking;
use Carbon\Carbon;

class BookingPageController extends Controller
{
    public function showBookNowPage(Request $request)
    {
        $monthParam = $request->query('month');
        $currentMonth = $monthParam 
            ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $calendarDays = $this->generateCalendarDays($currentMonth);

        return view('dashboards.student.book_now', compact('calendarDays', 'currentMonth'));
    }

    private function generateCalendarDays(Carbon $currentMonth)
    {
        $startDate = $currentMonth->copy()->startOfMonth()->startOfWeek();
        $endDate = $currentMonth->copy()->endOfMonth()->endOfWeek();
        $days = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $days[] = [
                'date' => $date->copy(),
                'classes' => ClassModel::where('date', $date->toDateString())->get()
            ];
        }

        return $days;
    }

// public function myBookings()
// {
//     $user = auth()->user();

//     $pendingRequests = BookingRequest::where('user_id', $user->id)
//         ->where('status', 'Pending')
//         ->with('class')
//         ->get();

//     $confirmedBookings = Booking::where('user_id', $user->id)
//         ->with('class')
//         ->get();

//     return view('dashboards.student.my_bookings', compact('pendingRequests', 'confirmedBookings'));
// }
public function myBookings(Request $request)
{
    $status = $request->query('status'); // e.g. 'Booked', 'Cancelled', etc.
    $user = auth()->user();

    $validStatuses = ['Cancelled', 'Absent', 'Rescheduled', 'Attended', 'Booked'];

    // Get pending booking requests separately
    $pendingRequests = \App\Models\BookingRequest::where('user_id', $user->id)
        ->where('status', 'Pending')
        ->with('class')
        ->get();

    // Filter actual bookings
    $query = $user->bookings()->with('class', 'rescheduleTickets');

    if ($status && in_array($status, $validStatuses)) {
        $filteredBookings = $query->where('status', $status)->get();
    } else {
        $filteredBookings = $query->get(); // Show all bookings
    }

    return view('dashboards.student.my_bookings', compact('pendingRequests', 'filteredBookings', 'status', 'validStatuses'));
}







}
