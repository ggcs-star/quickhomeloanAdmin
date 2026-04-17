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
            'title' => 'required'
        ]);

        EducationModule::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'order' => (int) ($request->order ?? 0),
            'status' => (int) ($request->status ?? 1),
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
            'title' => 'required'
        ]);

        $module = EducationModule::findOrFail($id);

        $module->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'order' => (int) ($request->order ?? 0),
            'status' => (int) ($request->status ?? 1),
        ]);

        return redirect()->route('modules.index')->with('success', 'Module Updated');
    }

    public function destroy($id)
    {
        EducationModule::findOrFail($id)->delete();
        return redirect()->route('modules.index')->with('success', 'Module Deleted');
    }
}