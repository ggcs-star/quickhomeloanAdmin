@extends('layouts.admin')
@section('title', 'Edit Module')

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
            <h1 class="text-2xl font-bold text-gray-900">Edit Module</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update module information and settings</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Form Header -->
        <div class="px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-purple-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Editing: {{ $module->title }}</h2>
                    <p class="text-sm text-gray-500">Last updated: {{ $module->updated_at ? $module->updated_at->diffForHumans() : 'Never' }}</p>
                </div>
                <!-- Status Badge -->
                <span class="ml-auto px-3 py-1 text-xs font-semibold rounded-full shadow-sm
                           {{ $module->status ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $module->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('modules.update', $module->_id) }}" method="POST" enctype="multipart/form-data" class="p-8">
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white cursor-pointer">
                        @foreach($courses as $course)
                            <option value="{{ $course->_id }}" {{ $module->course_id == $course->_id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Module Title -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/>
                        </svg>
                        Module Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ $module->title }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
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
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ $module->description }}</textarea>
                </div>

                <!-- Three Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Current Image -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Current Image</label>
                        @if($module->image)
                            <img src="{{ asset('storage/'.$module->image) }}" 
                                 class="w-full h-32 object-cover rounded-xl border shadow-sm">
                        @else
                            <div class="w-full h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                <span class="text-sm text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Replace Image -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Replace Image</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-indigo-400 transition cursor-pointer"
                             onclick="document.getElementById('image').click()">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mt-2 text-xs text-gray-500">Click to change</p>
                            </div>
                            <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </div>
                        <div id="imagePreview" class="hidden mt-2">
                            <img id="preview" src="#" alt="New Preview" class="w-full h-32 object-cover rounded-lg">
                        </div>
                    </div>

                    <!-- Color & Order -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Color Theme</label>
                            <input type="color" name="color_code" value="{{ $module->color_code ?? '#6366F1' }}"
                                   class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Display Order</label>
                            <input type="number" name="order" min="0" value="{{ $module->order }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>
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
                            <input type="radio" name="status" value="1" {{ $module->status ? 'checked' : '' }}
                                   class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-medium">✅ Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="0" {{ !$module->status ? 'checked' : '' }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update Module
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