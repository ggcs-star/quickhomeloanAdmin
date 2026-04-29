@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('calculator-media.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Edit Media: {{ $media->title }}</h2>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('calculator-media.update', $media->_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Calculator Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Calculator</label>
                        <select name="calculator_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">Choose a calculator...</option>
                            @foreach($calculators as $calc)
                                <option value="{{ $calc->_id }}" {{ $media->calculator_id == $calc->_id ? 'selected' : '' }}>
                                    {{ $calc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('calculator_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $media->title) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Media Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Media Type</label>
                        <select name="type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="video" {{ $media->type == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="audio" {{ $media->type == 'audio' ? 'selected' : '' }}>Audio</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current File Display -->
                    @if($media->file)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current File</label>
                            <div class="flex items-center justify-between">
                                <a href="{{ asset('storage/'.$media->file) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 underline text-sm">
                                    View Current File
                                </a>
                                <span class="text-xs text-gray-500">Leave empty to keep current file</span>
                            </div>
                        </div>
                    @endif

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Media File (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition duration-200">
                            <input type="file" 
                                   name="file" 
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Supported formats: MP4, MP3, WAV (Max: 100MB)</p>
                        </div>
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Thumbnail Display -->
                    @if($media->thumbnail)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Thumbnail</label>
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/'.$media->thumbnail) }}" 
                                     alt="Current thumbnail" 
                                     class="h-20 w-20 object-cover rounded-lg">
                                <span class="text-xs text-gray-500">Leave empty to keep current thumbnail</span>
                            </div>
                        </div>
                    @endif

                    <!-- Thumbnail Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Thumbnail (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition duration-200">
                            <input type="file" 
                                   name="thumbnail" 
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                            <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG (Max: 2MB)</p>
                        </div>
                        @error('thumbnail')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">{{ old('description', $media->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <a href="{{ route('calculator-media.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                            Update Media
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection