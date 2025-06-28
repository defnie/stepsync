<?php

namespace App\Http\Controllers\Resource;

use App\Models\Video;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['class', 'uploader'])->get();
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        $classes = ClassModel::all();  // So you can pick which class this video belongs to
        return view('videos.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'video_url' => 'required|url',
        ]);

        Video::create([
            'class_id' => $request->class_id,
            'uploader_id' => auth()->id(),
            'video_url' => $request->video_url,
            'upload_date' => now(),
        ]);

        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully!');
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    public function edit(Video $video)
    {
        $classes = ClassModel::all();
        return view('videos.edit', compact('video', 'classes'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'video_url' => 'required|url',
        ]);

        $video->update([
            'class_id' => $request->class_id,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('videos.index')->with('success', 'Video deleted.');
    }
}
