<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('notifications.manage_notifications', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:student,instructor,admin',
        ]);

        Announcement::create($validated);

        return redirect()->route('notifications.announcements')->with('success', 'Announcement created successfully!');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:student,instructor,admin',
        ]);

        $announcement->update($validated);

        return redirect()->route('notifications.announcements')->with('success', 'Announcement updated!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('notifications.announcements')->with('success', 'Announcement deleted.');
    }
}
