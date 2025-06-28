<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use App\Models\Booking;

class BookingRequestController extends Controller
{
    public function index()
    {
        $requests = BookingRequest::with(['user', 'class'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.admin.manage_booking_requests', compact('requests'));
    }

    public function updateStatus($id, $status)
    {
        $request = BookingRequest::findOrFail($id);
        $request->update(['status' => $status]);

        if ($status === 'Confirmed') {
            $existing = Booking::where('user_id', $request->user_id)
                ->where('class_id', $request->class_id)
                ->first();

            if (!$existing) {
                Booking::create([
                    'user_id' => $request->user_id,
                    'class_id' => $request->class_id,
                    'booking_date' => now(),
                    'status' => 'Booked',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Booking status updated!');
    }

    // 🔻 Consider removing this if redundant (same as index)
    public function adminDashboard()
    {
        $requests = BookingRequest::with(['user', 'class'])->latest()->get();

        return view('dashboards.admin.manage_booking_requests', compact('requests'));
    }
}
