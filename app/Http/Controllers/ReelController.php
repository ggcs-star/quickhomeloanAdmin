<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reel;

class ReelController extends Controller
{
    public function index()
    {
        $reels = Reel::orderBy('order')->get();
        return view('reels.index', compact('reels'));
    }

    public function create()
    {
        return view('reels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|file'
        ]);

        $filePath = $request->file('file')->store('reels', 'public');

        Reel::create([
            'title' => $request->title,
            'description' => $request->description,
            'file' => $filePath,
            'order' => (int) ($request->order ?? 0),
            'is_active' => (int) ($request->is_active ?? 1),
            'views' => 0
        ]);

        return redirect()->route('reels.index')->with('success', 'Reel Created');
    }

    public function edit($id)
    {
        $reel = Reel::findOrFail($id);
        return view('reels.edit', compact('reel'));
    }

    public function update(Request $request, $id)
    {
        $reel = Reel::findOrFail($id);

        if ($request->hasFile('file')) {
            $reel->file = $request->file('file')->store('reels', 'public');
        }

        $reel->update([
            'title' => $request->title,
            'description' => $request->description,
            'order' => (int) ($request->order ?? 0),
            'is_active' => (int) ($request->is_active ?? 1),
        ]);

        return redirect()->route('reels.index')->with('success', 'Reel Updated');
    }

    public function delete($id)
    {
        Reel::findOrFail($id)->delete();
        return back()->with('success', 'Reel Deleted');
    }
}