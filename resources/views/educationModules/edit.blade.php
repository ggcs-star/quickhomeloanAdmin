@extends('layouts.admin')
@section('title', 'Edit Module')

@section('content')

    <div class="p-6 space-y-6 max-w-4xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="flex items-center gap-4">
            <a href="{{ route('modules.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Module</h1>
                <p class="text-sm text-gray-500 mt-0.5">Update the module information</p>
            </div>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- FORM HEADER -->
            <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Editing: {{ $module->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Last updated:
                            {{ $module->updated_at ? $module->updated_at->diffForHumans() : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- FORM BODY -->
            <form action="{{ route('modules.update', $module->_id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">

                    <!-- TITLE FIELD -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                            </svg>
                            Module Title
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" required value="{{ $module->title }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    </div>
                    @if($module->image)
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Current Image</label>
                            <img src="{{ asset('storage/' . $module->image) }}" class="w-24 h-24 object-cover rounded border">
                        </div>
                    @endif
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Replace Image</label>

                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 border rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Color Theme</label>

                        <input type="color" name="color_code" value="{{ $module->color_code ?? '#6366F1' }}"
                            class="w-20 h-10 border rounded">
                    </div>
                    <!-- DESCRIPTION FIELD -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Description
                        </label>
                        <textarea name="description" rows="5"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 resize-none">{{ $module->description }}</textarea>
                    </div>

                    <!-- TWO COLUMN LAYOUT -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- ORDER FIELD -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                Display Order
                            </label>
                            <input type="number" name="order" min="0" value="{{ $module->order }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- STATUS FIELD -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status
                            </label>
                            <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                                <option value="1" {{ $module->status ? 'selected' : '' }}>✅ Active - Visible to users
                                </option>
                                <option value="0" {{ !$module->status ? 'selected' : '' }}>⏸️ Inactive - Hidden from users
                                </option>
                            </select>
                        </div>

                    </div>

                </div>

                <!-- FORM ACTIONS -->
                <div class="flex items-center gap-3 pt-8 mt-2 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Update Module
                    </button>

                    <a href="{{ route('modules.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all duration-200">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>

@endsection