@extends('layouts.admin')

@section('title', $podcast->title)

@section('content')

@php

preg_match(
    '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/',
    $podcast->youtube_url,
    $matches
);

$videoId = $matches[1] ?? '';

@endphp

<div class="p-6 max-w-6xl mx-auto">

    <div class="mb-6">

        <h2 class="text-3xl font-bold text-gray-800">
            {{ $podcast->title }}
        </h2>

        <div class="flex items-center gap-6 mt-3 text-sm text-gray-500">

            <span>
                🎙 {{ $podcast->episodes_count }} Episodes
            </span>

            <span>
                ⏱ {{ $podcast->total_duration }}
            </span>

        </div>

    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <iframe
            width="100%"
            height="600"
            src="https://www.youtube.com/embed/{{ $videoId }}"
            title="{{ $podcast->title }}"
            frameborder="0"
            allowfullscreen
            class="w-full">
        </iframe>

    </div>

</div>

@endsection
