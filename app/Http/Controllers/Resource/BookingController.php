<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use App\Models\RescheduleTicket;
use App\Models\ClassModel;
use App\Models\Booking;
use Carbon\Carbon;


class BookingController extends Controller
{
    public function bookNow(Request $request, $classId)
{
    $user = auth()->user();

    // Delete if already exists
    $existingRequest = BookingRequest::where('user_id', $user->id)
        ->where('class_id', $classId)
        ->where('status', 'Pending')
        ->first();

    if ($existingRequest) {
        $existingRequest->delete();
        return back()->with('success', 'Booking request cancelled.');
    }

    $paymentType = $request->payment_type;

    // Check reschedule ticket if selected
    $rescheduleTicketId = null;
    if ($paymentType === 'Reschedule') {
        $ticket = RescheduleTicket::where('user_id', $user->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$ticket) {
            return back()->with('error', 'No valid reschedule ticket found.');
        }

        $rescheduleTicketId = $ticket->id;
    }

    BookingRequest::create([
        'user_id' => $user->id,
        'class_id' => $classId,
        'payment_type' => $paymentType,
        'reschedule_ticket_id' => $rescheduleTicketId,
        'status' => 'Pending',
    ]);

    // Prepare WA
    $class = \App\Models\ClassModel::findOrFail($classId);
    $msg = $paymentType === 'Reschedule'
        ? "I want to use my reschedule ticket for class {$class->title} on {$class->date} at " . \Carbon\Carbon::parse($class->start_time)->format('H:i')
        : "I want to book class {$class->title} on {$class->date} at " . \Carbon\Carbon::parse($class->start_time)->format('H:i') . ", payment type: Transfer";

    $waText = urlencode($msg);
    $waUrl = "https://wa.me/6285814414730?text=$waText"; // Replace

    return redirect($waUrl);
}
public function checkRescheduleEligibility($classId)
{
    $user = auth()->user();
    $class = \App\Models\ClassModel::findOrFail($classId);

    $ticket = \App\Models\RescheduleTicket::where('user_id', $user->id)
        ->where('used', false)
        ->where('expires_at', '>=', $class->date) // Make sure ticket doesn't expire before class
        ->first();

    if (!$ticket) {
        return response()->json([
            'eligible' => false,
            'message' => 'You don’t have a valid reschedule ticket for this class.',
        ]);
    }

    return response()->json(['eligible' => true]);
}
public function requestReschedule($bookingId)
{
    $booking = Booking::with('class')->findOrFail($bookingId);

    if (now()->diffInHours($booking->class->date . ' ' . $booking->class->start_time, false) < 24) {
        return back()->with('error', 'Rescheduling is only allowed 24h before the class.');
    }

    if ($booking->status === 'Rescheduled') {
        return back()->with('error', 'This booking was already rescheduled.');
    }

    RescheduleTicket::create([
        'user_id' => auth()->id(),
        'original_booking_id' => $booking->id,
        'expires_at' => Carbon::parse($booking->class->date)->addDays(14),
        'used' => false,
    ]);

    $booking->update(['status' => 'Rescheduled']);

    return back()->with('success', 'Reschedule ticket created!');
}
}
