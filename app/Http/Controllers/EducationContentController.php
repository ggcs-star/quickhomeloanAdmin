<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationContent;
use App\Models\EducationModule;
use Illuminate\Support\Str;

class EducationContentController extends Controller
{
    public function index()
    {
        $contents = EducationContent::with('module')
            ->orderBy('order')
            ->paginate(10);

        return view('educationContents.index', compact('contents'));
    }

    public function show($id)
    {
        $content = EducationContent::with('module')->findOrFail($id);

        return view('educationContents.show', compact('content'));
    }

    public function create()
    {
        $modules = EducationModule::where('status', 1)->orderBy('order')->get();
        return view('educationContents.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'file' => 'nullable|file',
            'thumbnail' => 'nullable|image'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('education/files', 'public');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('education/thumbnails', 'public');
        }
        $faqs = array_values(array_filter($request->faqs ?? [], function ($faq) {
            return !empty($faq['title']) || !empty($faq['description']);
        }));
        EducationContent::create([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'type' => $request->type,
            'file' => $filePath,
            'thumbnail' => $thumbnailPath,
            'duration' => $request->duration,
            'description' => $request->description,
            'order' => (int) ($request->order ?? 0),
            'status' => (int) ($request->status ?? 1),
            'faqs' => $faqs,
        ]);

        return redirect()->route('contents.index')->with('success', 'Content Created');
    }

    public function edit($id)
    {
        $content = EducationContent::findOrFail($id);
        $modules = EducationModule::where('status', 1)->orderBy('order')->get();

        return view('educationContents.edit', compact('content', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'file' => 'nullable|file',
            'thumbnail' => 'nullable|image'
        ]);

        $content = EducationContent::findOrFail($id);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('education/files', 'public');
            $content->file = $filePath;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('education/thumbnails', 'public');
            $content->thumbnail = $thumbnailPath;
        }
        $faqs = array_values(array_filter($request->faqs ?? [], function ($faq) {
            return !empty($faq['title']) || !empty($faq['description']);
        }));
        $content->update([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'type' => $request->type,
            'duration' => $request->duration,
            'description' => $request->description,
            'order' => (int) ($request->order ?? 0),
            'status' => (int) ($request->status ?? 1),
            'faqs' => $faqs,
        ]);

        return redirect()->route('contents.index')->with('success', 'Content Updated');
    }

    public function destroy($id)
    {
        EducationContent::findOrFail($id)->delete();
        return redirect()->route('contents.index')->with('success', 'Content Deleted');
    }
}