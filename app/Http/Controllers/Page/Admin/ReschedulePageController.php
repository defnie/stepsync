<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use App\Models\RescheduleTicket;

class ReschedulePageController extends Controller
{
    public function index()
    {
        $tickets = RescheduleTicket::with([
                'user',
                'originalBooking',
                'originalBooking.classModel'
            ])
            ->orderBy('expires_at')
            ->get();

        return view('dashboards.admin.manage_reschedule_tickets', compact('tickets'));
    }
}

