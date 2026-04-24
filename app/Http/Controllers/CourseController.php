<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class CourseController extends Controller
{

    public function index()
    {
        $courses = Course::orderBy('order')->get();
        return view('courses.index', compact('courses'));
    }


    public function create()
    {
        return view('courses.create');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

 

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        
        $slug = Str::slug($request->title);

        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'title' => $request->title,
            'slug' => $slug, 
            'description' => $request->description,
            'image' => $imagePath,
            'status' => true,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course Added');
    }




    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        $slug = $course->slug;

        if ($course->title !== $request->title) {

            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;

            while (Course::where('slug', $slug)->where('_id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }


        if ($request->hasFile('image')) {

            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $course->image = $request->file('image')->store('courses', 'public');
        }

        $course->update([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'order' => $request->order,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course Updated');
    }


    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return back()->with('success', 'Course Deleted');
    }


    public function toggle($id)
    {
        $course = Course::findOrFail($id);
        $course->status = !$course->status;
        $course->save();

        return back();
    }
}