<?php

namespace App\Http\Controllers\Page\Student;

use App\Http\Controllers\Controller;
use App\Models\RescheduleTicket;

class ReschedulePageController extends Controller
{
    public function myReschedules()
    {
        $tickets = RescheduleTicket::where('user_id', auth()->id())
            ->with('originalBooking.class')
            ->latest()
            ->get();

        return view('dashboards.student.my_reschedules', [
            'rescheduleTickets' => $tickets
        ]);
    }
}
