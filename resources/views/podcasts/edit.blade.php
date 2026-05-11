
@extends('layouts.admin')

@section('title', 'Edit Podcast')

@section('content')

<div class="p-6 max-w-3xl mx-auto">

    <div class="mb-6">

        <h2 class="text-2xl font-bold text-gray-800">
            Edit Podcast
        </h2>

    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <form method="POST"
              action="{{ route('podcasts.update', $podcast->_id) }}"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Podcast Title
                </label>

                <input type="text"
                       name="title"
                       value="{{ $podcast->title }}"
                       class="w-full border rounded-lg px-4 py-3"
                       required>

            </div>

            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current Thumbnail
                </label>

                <img src="{{ asset('storage/' . $podcast->thumbnail) }}"
                     class="w-48 rounded-xl border mb-3">

                <input type="file"
                       name="thumbnail"
                       class="w-full border rounded-lg px-4 py-3">

            </div>

            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    YouTube URL
                </label>

                <input type="text"
                       name="youtube_url"
                       value="{{ $podcast->youtube_url }}"
                       class="w-full border rounded-lg px-4 py-3"
                       required>

            </div>

            <div class="grid grid-cols-2 gap-4">

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Episodes Count
                    </label>

                    <input type="number"
                           name="episodes_count"
                           value="{{ $podcast->episodes_count }}"
                           class="w-full border rounded-lg px-4 py-3"
                           required>

                </div>

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Duration
                    </label>

                    <input type="text"
                           name="total_duration"
                           value="{{ $podcast->total_duration }}"
                           class="w-full border rounded-lg px-4 py-3"
                           required>

                </div>

            </div>

            <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">

                Update Podcast

            </button>

        </form>

    </div>

</div>

@endsection
