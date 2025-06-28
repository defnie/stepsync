<?php

namespace App\Http\Controllers\Resource;

use App\Models\ClassModel;
use App\Models\User;
use App\Models\Choreography;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AttendanceReport;
use App\Models\ScheduleChangeNotification;
use App\Http\Controllers\Controller;



class ClassController extends Controller
{
    // ✅ Core CRUD

public function index(Request $request)
{
    $query = ClassModel::with(['instructor', 'bookings'])
        ->where('date', '<', now()); // Past classes only

    if ($request->filled('month')) {
        try {
            $start = \Carbon\Carbon::parse($request->month)->startOfMonth();
            $end = \Carbon\Carbon::parse($request->month)->endOfMonth();
            $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        } catch (\Exception $e) {
            // silently ignore invalid month input
        }
    }

    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%');
    }

    $classes = $query->get()->map(function ($class) {
        $class->attended_count = $class->bookings->where('attended', true)->count();
        $class->total_booked = $class->bookings->count();
        return $class;
    });

    return view('classes.history', compact('classes'));
}




    public function store(Request $request)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'style' => 'required|string',
                'difficulty' => 'required|string',
                'video_url' => 'required|url',
                'date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'max_slot' => 'required|integer|min:1',
            ]);

            // Create choreography
            $choreo = Choreography::create([
                'style' => $request->style,
                'difficulty' => $request->difficulty,
            ]);

            // Create class
            $class = ClassModel::create([
                'title' => $request->title,
                'location' => $request->location,
                'instructor_id' => auth()->user()->id,
                'choreography_id' => $choreo->id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_slot' => $request->max_slot,
            ]);

                // Create video linked to class
            $class->videos()->create([
                'video_url' => $request->video_url,
                'uploader_id' => auth()->user()->id
            ]);
            return redirect()->route('instructor.classes.index')->with('success', 'Kelas berhasil ditambahkan!');
        }

    public function update(Request $request, $id)
        {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'choreography_id' => 'required|exists:choreographies,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_slot' => 'required|integer|min:1',
            'video_url' => 'required|url',
        ]);

            $class = ClassModel::findOrFail($id);

            // Capture original values
            $originalDate = $class->date;
            $originalTime = $class->start_time;

            // Update the class
            $class->update([
                'title' => $validated['title'],
                'location' => $validated['location'],
                'choreography_id' => $validated['choreography_id'],
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'max_slot' => $validated['max_slot'],
            ]);

            // Update or create video
            $class->videos()->updateOrCreate(
                ['class_id' => $class->id],
                [
                    'video_url' => $validated['video_url'],
                    'uploader_id' => auth()->user()->id
                ]
            );

            // Check if schedule changed (either date or time)
            if ($originalDate !== $validated['date'] || $originalTime !== $validated['start_time']) {
                ScheduleChangeNotification::create([
                    'class_id' => $class->id,
                    'old_time' => $originalTime,
                    'new_time' => $validated['start_time'],
                    'sent_at' => now(),
                ]);
            }

            return redirect()->route('instructor.classes.index')->with('success', 'Class and video updated!');
        }

    // ✅ Admin-only update
public function updateAdmin(Request $request, $id)
            {

                $validated = $request->validate([
                    'title' => 'required|string',
                    'location' => 'required|string',
                    'date' => 'required|date',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    'max_slot' => 'required|integer',
                    'choreography_id' => 'required|integer|exists:choreographies,id',
                    'status' => 'required|in:Active,Cancelled,Expired,Absent',
                ]);

                $class = ClassModel::findOrFail($id);
                $class->update($validated);

                return redirect()->route('dashboards.admin.manage_classes')->with('success', 'Class updated successfully');
            }



    public function destroy($id)
        {
            $class = ClassModel::findOrFail($id);
            $class->delete();

            return redirect()->route('instructor.classes.index')->with('success', 'Class deleted.');
        }
    public function destroyAdmin($id)
        {
            $class = ClassModel::findOrFail($id);
            $class->delete();

            return redirect()->route('admin.classes.index')->with('success', 'Class deleted.');
        }

    public function show($id)
        {
            $class = ClassModel::with(['instructor', 'choreography'])->findOrFail($id);
            return view('classes.show', compact('class'));
        }

    //attendance functions :


public function updateDocs(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        $class->update([
            'doc_name1' => $request->doc_name1,
            'doc_link1' => $request->doc_link1,
            'doc_name2' => $request->doc_name2,
            'doc_link2' => $request->doc_link2,
            'doc_name3' => $request->doc_name3,
            'doc_link3' => $request->doc_link3,
        ]);

        return redirect()->route('instructor.history')->with('success', 'Documentation updated successfully!');
    }


}
