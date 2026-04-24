@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Course</h2>
        </div>
        <p class="text-sm text-gray-500 ml-8">Update course information</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('courses.update', $course->_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Course Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title"
                    value="{{ old('title', $course->title) }}"
                    placeholder="Enter course title"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" id="description" rows="5"
                    placeholder="Enter course description"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order"
                        value="{{ old('order', $course->order) }}"
                        placeholder="0"
                        min="0"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Slug (Read-only) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Slug
                    </label>
                    <input type="text" value="{{ $course->slug }}"
                        class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Course Image
                </label>
                
                @if($course->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                        <img src="{{ asset('storage/'.$course->image) }}"
                             class="w-48 h-32 object-cover rounded-lg shadow-sm">
                    </div>
                @endif
                
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition cursor-pointer"
                     onclick="document.getElementById('image').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <span class="relative font-medium text-blue-600 hover:text-blue-500">Change image</span>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                <input type="file" name="image" id="image" class="hidden" accept="image/*"
                       onchange="previewImage(this)">
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-500 mb-2">New Image Preview:</p>
                    <img id="preview" src="#" alt="Preview" class="w-48 h-32 object-cover rounded-lg">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t">
                <a href="{{ route('courses.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update Course
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection