<?php

namespace App\Http\Controllers\Page\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\Choreography;

use App\Models\AttendanceReport;
use Carbon\Carbon;


class ClassPageController extends Controller
{
    // Instructor dashboard class listing
    public function index()
        {
            $now = now();

            // Actively update status for each class
            ClassModel::all()->each(function ($class) use ($now) {
                $start = \Carbon\Carbon::parse("{$class->date} {$class->start_time}");
                $end = \Carbon\Carbon::parse("{$class->date} {$class->end_time}");
            if ($now->gt($end) && $class->status !== 'Completed') {
                $class->status = 'Completed';
                $class->save();
            }
            elseif ($now->between($start, $end) && $class->status !== 'In Session') {
                $class->status = 'In Session';
                $class->save();
            }
            elseif ($now->lt($start) && $class->status !== 'Upcoming') {
                $class->status = 'Upcoming';
                $class->save();
            }


            });

            // Now load based on updated status
            $classesInSession = ClassModel::where('status', 'In Session')
                ->with(['instructor', 'videos'])
                ->get();

            $otherClasses = ClassModel::where('status', '!=', 'In Session')
                ->with(['instructor', 'videos'])
                ->get();

            $choreographies = Choreography::all();
            $instructors = User::all();

            return view('dashboards.instructor.manage_my_classes', compact(
                'classesInSession',
                'otherClasses',
                'choreographies',
                'instructors'
            ));
        }

    public function create()
        {
            $instructors = User::all();
            $choreographies = Choreography::all();
            return view('dashboards.instructor.manage_my_classes', compact('instructors', 'choreographies'));
        }
    public function edit($id)
        {
            $class = ClassModel::findOrFail($id);
            $instructors = User::all();
            $choreographies = Choreography::all();

            $now = now();

            $classesInSession = ClassModel::whereDate('date', $now->toDateString())
                ->where('start_time', '<=', $now->format('H:i:s'))
                ->where('end_time', '>=', $now->format('H:i:s'))
                ->with(['instructor', 'videos'])
                ->get();

            $otherClasses = ClassModel::whereNotIn('id', $classesInSession->pluck('id'))
                ->with(['instructor', 'videos'])
                ->get();

            return view('dashboards.instructor.manage_my_classes', compact(
                'class',
                'instructors',
                'choreographies',
                'classesInSession',
                'otherClasses'
            ));
        }
   public function history()
    {
        $classes = ClassModel::where('status', 'Completed')
            ->with(['instructor'])
            ->withCount(['bookings as total_booked'])
            ->get();

        foreach ($classes as $class) {
            $class->attended_count = AttendanceReport::where('class_id', $class->id)
                ->where('attended', true)
                ->count();
        }

        return view('classes.history', compact('classes'));
    } 

    }
