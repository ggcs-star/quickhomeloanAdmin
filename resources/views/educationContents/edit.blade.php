@extends('layouts.admin')
@section('title', 'Edit Content')

@section('content')

<div class="p-6 space-y-6 max-w-4xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="flex items-center gap-4">
        <a href="{{ route('contents.index') }}" 
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Content</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update content information</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- FORM HEADER -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Editing: {{ $content->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Last updated: {{ $content->updated_at ? $content->updated_at->diffForHumans() : 'Never' }}</p>
                </div>
            </div>
        </div>

        <!-- FORM BODY -->
        <form action="{{ route('contents.update', $content->_id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="space-y-6">
                
                <!-- MODULE SELECTION -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Select Module
                    </label>
                    <select name="module_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                        @foreach($modules as $module)
                            <option value="{{ $module->_id }}" {{ $content->module_id == $module->_id ? 'selected' : '' }}>
                                {{ $module->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- TITLE FIELD -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/>
                        </svg>
                        Content Title
                    </label>
                    <input type="text" name="title" value="{{ $content->title }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- CONTENT TYPE -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        Content Type
                    </label>
                    <select name="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                        <option value="video" {{ $content->type == 'video' ? 'selected' : '' }}>🎬 Video</option>
                        <option value="audio" {{ $content->type == 'audio' ? 'selected' : '' }}>🎵 Audio</option>
                        <option value="text" {{ $content->type == 'text' ? 'selected' : '' }}>📄 Text</option>
                    </select>
                </div>

                <!-- CURRENT FILE PREVIEW -->
                @if($content->file)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Current File</label>
                    @if($content->type == 'audio')
                        <audio controls class="w-full">
                            <source src="{{ asset('storage/'.$content->file) }}">
                        </audio>
                    @elseif($content->type == 'video')
                        <video controls class="w-full rounded-lg">
                            <source src="{{ asset('storage/'.$content->file) }}">
                        </video>
                    @else
                        <a href="{{ asset('storage/'.$content->file) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-indigo-600 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            View Current File
                        </a>
                    @endif
                </div>
                @endif

                <!-- REPLACE FILE -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Replace File (Optional)
                    </label>
                    <input type="file" name="file"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- CURRENT THUMBNAIL PREVIEW -->
                @if($content->thumbnail)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Current Thumbnail</label>
                    <img src="{{ asset('storage/'.$content->thumbnail) }}" 
                         class="w-32 h-32 rounded-lg object-cover border border-gray-200 shadow-sm">
                </div>
                @endif

                <!-- REPLACE THUMBNAIL -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Replace Thumbnail (Optional)
                    </label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- DURATION AND ORDER -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Duration
                        </label>
                        <input type="text" name="duration" value="{{ $content->duration }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Display Order
                        </label>
                        <input type="number" name="order" value="{{ $content->order }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    </div>

                </div>

                <!-- DESCRIPTION -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 resize-none">{{ $content->description }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                        <option value="1" {{ $content->status ? 'selected' : '' }}>✅ Active - Visible to users</option>
                        <option value="0" {{ !$content->status ? 'selected' : '' }}>⏸️ Inactive - Hidden from users</option>
                    </select>
                </div>

            </div>

            <!-- FORM ACTIONS -->
            <div class="flex items-center gap-3 pt-8 mt-2 border-t border-gray-200">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update Content
                </button>

                <a href="{{ route('contents.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all duration-200">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

@endsection