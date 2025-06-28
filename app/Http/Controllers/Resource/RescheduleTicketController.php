<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\RescheduleTicket;
use Illuminate\Http\Request;

class RescheduleTicketController extends Controller
{
    public function deleteExpired()
    {
        $count = RescheduleTicket::where('expires_at', '<', now())->delete();
        return redirect()->route('admin.reschedule_tickets')
            ->with('success', "$count expired ticket(s) deleted.");
    }

    public function useTicket(RescheduleTicket $ticket)
    {
        if ($ticket->used) {
            return back()->with('success', 'Ticket was already used.');
        }

        $ticket->update(['used' => true]);

        return back()->with('success', 'Ticket marked as used successfully!');
    }
}
