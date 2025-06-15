<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = $this->loadCourses();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $courses = $this->loadCourses();
        return view('courses.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'nullable',
            'modules' => 'required|array',
            'modules.*.title' => 'required',
            'modules.*.contents' => 'required|array',
            'modules.*.contents.*.type' => 'required',
            'modules.*.contents.*.data' => 'required',
        ]);

        $courses = $this->loadCourses();

        $courseId = Str::uuid()->toString();

        foreach ($validated['modules'] as $mIndex => $module) {
            foreach ($module['contents'] as $cIndex => $content) {
                if ($content['type'] === 'image' && $request->hasFile("modules.$mIndex.contents.$cIndex.data")) {
                    $file = $request->file("modules.$mIndex.contents.$cIndex.data");
                    $validated['modules'][$mIndex]['contents'][$cIndex]['data'] = $file->store('content_images', 'public');
                }
            }
        }

        $courses[$courseId] = [
            'id' => $courseId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'modules' => $validated['modules'],
        ];

        Storage::put('courses.json', json_encode($courses, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Course created successfully!');
    }

    public function delete($id)
    {
        $courses = $this->loadCourses();

        if (!isset($courses[$id])) {
            return response()->json(['success' => false, 'message' => 'Course not found.'], 404);
        }

        unset($courses[$id]);

        Storage::put('courses.json', json_encode($courses, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }

    private function loadCourses()
    {
        $json = Storage::exists('courses.json') ? Storage::get('courses.json') : '{}';
        return json_decode($json, true);
    }
}
