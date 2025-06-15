<?php


namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('modules.contents')->latest()->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
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

        $course = Course::create($request->only(['title', 'description', 'category']));

        foreach ($validated['modules'] as $key => $moduleData) {
            $module = $course->modules()->create([
                'title' => $moduleData['title'],
            ]);

            foreach ($moduleData['contents'] as $contentIndex => $contentData) {
                $contentType = $contentData['type'];
                $dataValue = $contentData['data'];

                if ($contentType === 'image' && $request->hasFile("modules.{$key}.contents.{$contentIndex}.data")) {
    $file = $request->file("modules.{$key}.contents.{$contentIndex}.data");
    // Store on the 'public' disk, in 'content_images' folder
    $path = $file->store('content_images', 'public');
    $dataValue = $path; // just store the path as returned
}


                $module->contents()->create([
                    'type' => $contentType,
                    'data' => $dataValue,
                ]);
            }
        }


        return redirect()->back()->with('success', 'Course created successfully!');
    }
}
