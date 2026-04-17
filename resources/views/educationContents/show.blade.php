@extends('layouts.admin')
@section('title', 'View Content')

@section('content')

<div class="p-6 space-y-6 max-w-5xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="flex items-center gap-4">
        <a href="{{ route('contents.index') }}" 
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Details</h1>
            <p class="text-sm text-gray-500 mt-0.5">View content information and media</p>
        </div>
    </div>

    <!-- CONTENT CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- CONTENT HEADER -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        {{ $content->type === 'video' ? 'bg-blue-100' : ($content->type === 'audio' ? 'bg-purple-100' : 'bg-green-100') }}">
                        @if($content->type === 'video')
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        @elseif($content->type === 'audio')
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $content->title }}</h2>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $content->type === 'video' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' : 
                                   ($content->type === 'audio' ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-600/20' : 
                                   'bg-green-50 text-green-700 ring-1 ring-green-600/20') }}">
                                {{ ucfirst($content->type) }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                {{ $content->status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 
                                        {{ $content->status ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 
                                        {{ $content->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                </span>
                                {{ $content->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('contents.edit', $content->_id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-sm font-medium transition-all duration-200 border border-amber-200/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Content
                    </a>
                </div>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="p-8 space-y-6">
            
            <!-- INFO GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Module</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $content->module->title ?? '—' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Duration</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $content->duration ?: 'Not specified' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Display Order</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">#{{ $content->order ?: 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Created</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $content->created_at ? $content->created_at->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>

            <!-- DESCRIPTION -->
            @if($content->description)
            <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $content->description }}</p>
            </div>
            @endif

            <!-- THUMBNAIL -->
            @if($content->thumbnail)
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900">Thumbnail Preview</h3>
                <img src="{{ asset('storage/'.$content->thumbnail) }}" 
                     class="w-full max-w-md rounded-xl border border-gray-200 shadow-sm">
            </div>
            @endif

            <!-- MEDIA PLAYER -->
            @if($content->file)
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-900">Content Media</h3>
                <div class="bg-gray-900 rounded-xl p-4">
                    @if($content->type == 'audio')
                        <audio controls class="w-full">
                            <source src="{{ asset('storage/'.$content->file) }}">
                            Your browser does not support the audio element.
                        </audio>
                    @elseif($content->type == 'video')
                        <video controls class="w-full rounded-lg">
                            <source src="{{ asset('storage/'.$content->file) }}">
                            Your browser does not support the video element.
                        </video>
                    @else
                        <a href="{{ asset('storage/'.$content->file) }}" target="_blank"
                           class="inline-flex items-center gap-3 px-5 py-3 bg-white rounded-xl text-indigo-600 hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Open Document File</span>
                        </a>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- FOOTER ACTIONS -->
        <div class="px-8 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between">
            <a href="{{ route('contents.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition-all duration-200 border border-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Contents
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('contents.delete', $content->_id) }}"
                   onclick="return confirm('Are you sure you want to delete this content? This action cannot be undone.')"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition-all duration-200 border border-red-200/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Content
                </a>
            </div>
        </div>

    </div>

</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>

@endsection