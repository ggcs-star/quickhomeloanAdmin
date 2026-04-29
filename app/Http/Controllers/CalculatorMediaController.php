<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalculatorMedia;
use App\Models\Calculator;
use Illuminate\Support\Str;

class CalculatorMediaController extends Controller
{
      public function index()
    {
        $media = CalculatorMedia::with('calculator')->latest()->get();
        return view('calculator_media.index', compact('media'));
    }

    public function create()
    {
        $calculators = Calculator::all();
        return view('calculator_media.create', compact('calculators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'calculator_id' => 'required',
            'title' => 'required',
            'type' => 'required|in:audio,video',
        ]);


        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('calculator/media', 'public');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('calculator/thumbnails', 'public');
        }


        CalculatorMedia::create([
            'calculator_id' => $request->calculator_id,
            'title' => $request->title,
            'slug' => \Str::slug($request->title),
            'type' => $request->type,
            'file' => $filePath,
            'thumbnail' => $thumbnailPath,
            'duration' => $request->duration,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'status' => $request->status ?? true,

        ]);

        return redirect()->route('calculator-media.index')
            ->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $media = CalculatorMedia::findOrFail($id);
        $calculators = Calculator::all();

        return view('calculator_media.edit', compact('media', 'calculators'));
    }

    public function update(Request $request, $id)
    {
        $media = CalculatorMedia::findOrFail($id);


        $filePath = $media->file;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('calculator/media', 'public');
        }

        $thumbnailPath = $media->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('calculator/thumbnails', 'public');
        }


        $media->update([
            'calculator_id' => $request->calculator_id,
            'title' => $request->title,
            'slug' => \Str::slug($request->title),
            'type' => $request->type,
            'file' => $filePath,
            'thumbnail' => $thumbnailPath,
            'duration' => $request->duration,
            'description' => $request->description,
            'order' => $request->order ?? $media->order,
            'status' => $request->status ?? $media->status,

        ]);

        return redirect()->route('calculator-media.index')
            ->with('success', 'Updated Successfully');
    }

    public function destroy($id)
    {
        CalculatorMedia::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }
}