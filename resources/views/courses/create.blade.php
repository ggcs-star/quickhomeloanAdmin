@extends('layouts.admin')

@section('title', 'Add New Course')

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
            <h2 class="text-2xl font-bold text-gray-800">Add New Course</h2>
        </div>
        <p class="text-sm text-gray-500 ml-8">Fill in the details to create a new course</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Course Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title"
                    value="{{ old('title') }}"
                    placeholder="Enter course title"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" 
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
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order"
                        value="{{ old('order', 0) }}"
                        placeholder="0"
                        min="0"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="1" checked
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="0"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Course Image
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition cursor-pointer"
                     onclick="document.getElementById('image').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <span class="relative font-medium text-blue-600 hover:text-blue-500">Upload a file</span>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                <input type="file" name="image" id="image" class="hidden" accept="image/*"
                       onchange="previewImage(this)">
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="preview" src="#" alt="Preview" class="w-40 h-40 object-cover rounded-lg">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Course
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