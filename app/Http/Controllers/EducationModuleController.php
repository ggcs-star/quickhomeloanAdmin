<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationModule;
use Illuminate\Support\Str;
use App\Models\Course;  
class EducationModuleController extends Controller
{
    public function index()
    {
        $modules = EducationModule::with([
            'contents' => function ($q) {
                $q->orderBy('order');
            }
        ])
            ->orderBy('order')
            ->paginate(10);

        return view('educationModules.index', compact('modules'));
    }
  public function create()
{
    $courses = Course::where('status', true)->orderBy('order')->get();

    return view('educationModules.create', compact('courses'));
}


public function store(Request $request)
{
    $request->validate([
        'course_id' => 'required',
        'title' => 'required',
        'image' => 'nullable|image',
        'color_code' => 'nullable|string'
    ]);

    
    $slug = Str::slug($request->title);
    $originalSlug = $slug;
    $count = 1;

    while (EducationModule::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('education/modules', 'public');
    }

    EducationModule::create([
        'course_id' => $request->course_id, 
        'title' => $request->title,
        'slug' => $slug,
        'description' => $request->description,
        'order' => (int) ($request->order ?? 0),
        'status' => (int) ($request->status ?? 1),
        'image' => $imagePath,
        'color_code' => $request->color_code ?? '#6366F1',
    ]);

    return redirect()->route('modules.index')->with('success', 'Module Created');
}

   public function edit($id)
{
    $module = EducationModule::findOrFail($id);
    $courses = Course::where('status', true)->orderBy('order')->get();

    return view('educationModules.edit', compact('module', 'courses'));
}


   public function update(Request $request, $id)
{
    $request->validate([
        'course_id' => 'required',
        'title' => 'required',
        'image' => 'nullable|image',
        'color_code' => 'nullable|string'
    ]);

    $module = EducationModule::findOrFail($id);

  
    $slug = Str::slug($request->title);
    $originalSlug = $slug;
    $count = 1;

    while (
        EducationModule::where('slug', $slug)
            ->where('_id', '!=', $id)
            ->exists()
    ) {
        $slug = $originalSlug . '-' . $count++;
    }

    if ($request->hasFile('image')) {
        $module->image = $request->file('image')->store('education/modules', 'public');
    }

    $module->update([
        'course_id' => $request->course_id,
        'title' => $request->title,
        'slug' => $slug,
        'description' => $request->description,
        'order' => (int) ($request->order ?? 0),
        'status' => (int) ($request->status ?? 1),
        'color_code' => $request->color_code ?? $module->color_code,
    ]);

    return redirect()->route('modules.index')->with('success', 'Module Updated');
}

    public function destroy($id)
    {
        EducationModule::findOrFail($id)->delete();
        return redirect()->route('modules.index')->with('success', 'Module Deleted');
    }
}