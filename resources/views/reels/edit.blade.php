@extends('layouts.admin')

@section('title', 'Edit Reel')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('reels.index') }}"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Edit Reel</h2>
            </div>
            <p class="text-sm text-gray-500 ml-8">Update the details and settings for this reel</p>
        </div>

        {{-- Form Section --}}
        <form method="POST" action="{{ route('reels.update', $reel->_id) }}" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                {{-- Last Updated Info --}}
                <div class="flex items-center justify-end">
                    <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Reel ID: <span class="font-mono text-gray-700">{{ $reel->_id }}</span></span>
                        @if($reel->updated_at)
                            <span class="text-gray-300">•</span>
                            <span>Last updated: {{ $reel->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                {{-- Title Field --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                        Reel Title <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="title" value="{{ old('title', $reel->title) }}" required
                            placeholder="Enter an engaging title for your reel"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('title') border-red-300 @enderror">
                        <div class="absolute right-3 bottom-3">
                            <span class="text-xs text-gray-400" x-data="{ count: {{ strlen($reel->title) }} }"
                                x-text="count + '/100'"></span>
                        </div>
                    </div>
                    @error('title')
                        <p class="text-sm text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Description
                    </label>
                    <div class="relative">
                        <textarea name="description" rows="4" placeholder="Provide a brief description of this reel..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none">{{ old('description', $reel->description) }}</textarea>
                        <div class="absolute right-3 bottom-3">
                            <span class="text-xs text-gray-400">Optional</span>
                        </div>
                    </div>
                </div>

                @if($reel->file)
                    <div class="space-y-2 max-w-sm"> {{-- overall width kam --}}
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Current Video
                        </label>

                        <div class="relative group">
                            <video controls class="w-full h-40 object-cover rounded-md border border-gray-200">
                                <source src="{{ asset('storage/' . $reel->file) }}">
                            </video>
                        </div>

                        <p class="text-xs text-gray-500">
                            Leave file upload empty to keep current video
                        </p>
                    </div>
                @endif

                {{-- Replace File Upload --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Replace Video <span class="text-xs font-normal text-gray-500">(Optional)</span>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-400 transition-colors duration-200 bg-gray-50"
                        x-data="{ fileName: '', isDragging: false }" @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false" @drop.prevent="
                            isDragging = false;
                            const file = $event.dataTransfer.files[0];
                            if (file && file.type.startsWith('video/')) {
                                fileName = file.name;
                                $refs.fileInput.files = $event.dataTransfer.files;
                            }
                         " :class="{ 'border-indigo-500 bg-indigo-50': isDragging }">
                        <input type="file" name="file" x-ref="fileInput"
                            @change="fileName = $event.target.files[0]?.name || ''" accept="video/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-indigo-600">Click to upload</span>
                                <span x-show="!fileName">or drag and drop</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" x-show="!fileName">
                                MP4, MOV, AVI up to 100MB
                            </p>
                            <div x-show="fileName" class="mt-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span x-text="fileName"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    @error('file')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order and Status Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Order Field --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            Display Order
                        </label>
                        <div class="relative">
                            <input type="number" name="order" value="{{ old('order', $reel->order) }}" min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <span class="text-xs text-gray-400">Higher = Later</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Field --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status
                        </label>
                        <div class="flex gap-4 p-3 border border-gray-300 rounded-lg bg-gray-50">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_active" value="1" {{ $reel->is_active ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_active" value="0" {{ !$reel->is_active ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Change Preview Card --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        What's Changing?
                    </h4>
                    <ul class="space-y-1.5 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 mt-0.5">•</span>
                            <span>Changes will be reflected immediately after update</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 mt-0.5">•</span>
                            <span>Current video will be kept if no new file is uploaded</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('reels.index') }}"
                    class="px-6 py-2.5 text-gray-700 hover:text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                    Cancel
                </a>

                <div class="flex gap-3">
                    <button type="reset"
                        class="px-6 py-2.5 text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                        Reset Changes
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Reel
                    </button>
                </div>
            </div>
        </form>

        {{-- Help Footer --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                Made changes by mistake? <a href="{{ route('reels.index') }}"
                    class="text-indigo-600 hover:text-indigo-700">Go back to reels</a>
            </p>
        </div>
    </div>

    {{-- Alpine.js for interactive features --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection