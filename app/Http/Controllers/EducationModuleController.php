<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationModule;
use Illuminate\Support\Str;

class EducationModuleController extends Controller
{
   public function index()
{
    $modules = EducationModule::with(['contents' => function ($q) {
            $q->orderBy('order');
        }])
        ->orderBy('order')
        ->paginate(10); 

    return view('educationModules.index', compact('modules'));
}
    public function create()
    {
        return view('educationModules.create');
    }


public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'image' => 'nullable|image',
        'color_code' => 'nullable|string'
    ]);

    
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('education/modules', 'public');
    }

    EducationModule::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
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
        return view('educationModules.edit', compact('module'));
    }

   
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required',
        'image' => 'nullable|image',
        'color_code' => 'nullable|string'
    ]);

    $module = EducationModule::findOrFail($id);

   
    if ($request->hasFile('image')) {
        $module->image = $request->file('image')->store('education/modules', 'public');
    }

    
    $module->update([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
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