@extends('layouts.admin')

@section('title', 'Podcasts')

@section('content')

<div class="p-6 max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Podcasts
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage podcast content
            </p>
        </div>

        <a href="{{ route('podcasts.create') }}"
           class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">

            + Add Podcast

        </a>

    </div>

    @if(session('success'))

        <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">

            {{ session('success') }}

        </div>

    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @forelse($podcasts as $podcast)

        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

            <img src="{{ asset('storage/' . $podcast->thumbnail) }}"
                 class="w-full h-56 object-cover">

            <div class="p-5">

                <h3 class="text-xl font-semibold text-gray-800">
                    {{ $podcast->title }}
                </h3>

                <div class="mt-4 flex items-center justify-between text-sm text-gray-500">

                    <span>
                        🎙 {{ $podcast->episodes_count }} Episodes
                    </span>

                    <span>
                        ⏱ {{ $podcast->total_duration }}
                    </span>

                </div>

                <div class="mt-5 flex items-center gap-3">

                    <a href="{{ route('podcasts.show', $podcast->_id) }}"
                       class="flex-1 text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">

                        Watch Now

                    </a>

                    <a href="{{ route('podcasts.edit', $podcast->_id) }}"
                       class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">

                        Edit

                    </a>

                    <form method="POST"
                          action="{{ route('podcasts.destroy', $podcast->_id) }}"
                          onsubmit="return confirm('Delete this podcast?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="px-4 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition">

                            Delete

                        </button>

                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="col-span-2 text-center py-20 bg-white rounded-2xl border">

            <h3 class="text-xl font-semibold text-gray-700">
                No Podcasts Found
            </h3>

        </div>

        @endforelse

    </div>

    <div class="mt-6">
        {{ $podcasts->links() }}
    </div>

</div>

@endsection
