@extends('layouts.admin')
@section('title', 'Add Module')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">
    
    <!-- PAGE HEADER -->
    <div class="flex items-center gap-4">
        <a href="{{ route('modules.index') }}" 
           class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200 group">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Module</h1>
            <p class="text-sm text-gray-500 mt-0.5">Add a new educational module to your platform</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Form Header with Gradient -->
        <div class="px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-purple-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Module Details</h2>
                    <p class="text-sm text-gray-500">Configure your new module settings</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="space-y-8">
                <!-- Course Selection -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Select Course <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white">
                        <option value="">-- Choose a course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->_id }}" {{ old('course_id') == $course->_id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Module Title -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/>
                        </svg>
                        Module Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required 
                           placeholder="e.g., Introduction to Programming Basics"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    @error('title')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Description
                    </label>
                    <textarea name="description" rows="5"
                              placeholder="Describe what this module covers and what students will learn..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Three Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Image Upload -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Module Image
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-indigo-400 transition cursor-pointer"
                             onclick="document.getElementById('image').click()">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mt-2 text-xs text-gray-500">Click to upload</p>
                            </div>
                            <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </div>
                        <div id="imagePreview" class="hidden mt-2">
                            <img id="preview" src="#" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                        </div>
                    </div>

                    <!-- Color Theme -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            Color Theme
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color_code" value="#6366F1"
                                   class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                            <span class="text-sm text-gray-500">Choose a theme color</span>
                        </div>
                    </div>

                    <!-- Display Order -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Display Order
                        </label>
                        <input type="number" name="order" min="0" value="{{ old('order', 0) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="1" checked
                                   class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-medium">✅ Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="0"
                                   class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-medium">⏸️ Inactive</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-3 pt-8 mt-8 border-t border-gray-200">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Module
                </button>
                <a href="{{ route('modules.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
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