<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Podcast;

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('podcasts.index', compact('podcasts'));
    }

    public function create()
    {
        return view('podcasts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'youtube_url' => 'required|string',
            'episodes_count' => 'required|integer',
            'total_duration' => 'required|string',
            'thumbnail' => 'nullable|image'
        ]);

        $thumbnail = null;

        if ($request->hasFile('thumbnail')) {

            $thumbnail = $request->file('thumbnail')
                ->store('podcasts', 'public');
        }

        $podcast = new Podcast();

        $podcast['title'] = (string) $request->title;
        $podcast['youtube_url'] = (string) $request->youtube_url;
        $podcast['episodes_count'] = (int) $request->episodes_count;
        $podcast['total_duration'] = (string) $request->total_duration;
        $podcast['thumbnail'] = $thumbnail;
        $podcast['is_active'] = true;

        $podcast->save();

        return redirect()
            ->route('podcasts.index')
            ->with('success', 'Podcast created successfully');
    }

    public function edit($id)
    {
        $podcast = Podcast::findOrFail($id);

        return view('podcasts.edit', compact('podcast'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'youtube_url' => 'required|string',
            'episodes_count' => 'required|integer',
            'total_duration' => 'required|string',
            'thumbnail' => 'nullable|image'
        ]);

        $podcast = Podcast::findOrFail($id);

        if ($request->hasFile('thumbnail')) {

            $thumbnail = $request->file('thumbnail')
                ->store('podcasts', 'public');

            $podcast['thumbnail'] = $thumbnail;
        }

        $podcast['title'] = (string) $request->title;
        $podcast['youtube_url'] = (string) $request->youtube_url;
        $podcast['episodes_count'] = (int) $request->episodes_count;
        $podcast['total_duration'] = (string) $request->total_duration;

        $podcast->save();

        return redirect()
            ->route('podcasts.index')
            ->with('success', 'Podcast updated successfully');
    }

    public function destroy($id)
    {
        $podcast = Podcast::findOrFail($id);

        $podcast->delete();

        return back()->with(
            'success',
            'Podcast deleted successfully'
        );
    }

    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);

        return view('podcasts.show', compact('podcast'));
    }
}
