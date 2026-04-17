@extends('layouts.admin')
@section('title', 'Add Module')

@section('content')

<div class="p-6 space-y-6 max-w-4xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="flex items-center gap-4">
        <a href="{{ route('modules.index') }}" 
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Module</h1>
            <p class="text-sm text-gray-500 mt-0.5">Add a new educational module to your platform</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- FORM HEADER -->
        <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
            <h2 class="text-lg font-semibold text-gray-900">Module Information</h2>
            <p class="text-sm text-gray-500 mt-1">Fill in the details for the new education module</p>
        </div>

        <!-- FORM BODY -->
        <form action="{{ route('modules.store') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-6">
                
                <!-- TITLE FIELD -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/>
                        </svg>
                        Module Title
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           placeholder="e.g., Introduction to Programming"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 placeholder:text-gray-400">
                    <p class="text-xs text-gray-500">Choose a clear and descriptive title for the module</p>
                </div>

                <!-- DESCRIPTION FIELD -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Description
                    </label>
                    <textarea name="description" rows="5"
                              placeholder="Provide a detailed description of what this module covers..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 resize-none placeholder:text-gray-400"></textarea>
                    <p class="text-xs text-gray-500">Optional but recommended for better context</p>
                </div>

                <!-- TWO COLUMN LAYOUT -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- ORDER FIELD -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Display Order
                        </label>
                        <input type="number" name="order" min="0"
                               placeholder="e.g., 1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <p class="text-xs text-gray-500">Lower numbers appear first</p>
                    </div>

                    <!-- STATUS FIELD -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </label>
                        <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                            <option value="1">✅ Active - Visible to users</option>
                            <option value="0">⏸️ Inactive - Hidden from users</option>
                        </select>
                    </div>

                </div>

            </div>

            <!-- FORM ACTIONS -->
            <div class="flex items-center gap-3 pt-8 mt-2 border-t border-gray-200">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Module
                </button>

                <a href="{{ route('modules.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

@endsection