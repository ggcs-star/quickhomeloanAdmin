@extends('layouts.admin')
@section('title', 'Education Modules')

@section('content')

<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Education Modules</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and organize your learning content</p>
        </div>

        <a href="{{ route('modules.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Module
        </a>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 animate-fade-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- STATS OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Modules</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $modules->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Active Modules</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $modules->where('status', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Contents</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $modules->sum(fn($m) => $m->contents->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODULES ACCORDION LIST -->
    <div class="space-y-4">
        @forelse($modules as $index => $module)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md"
             x-data="{ open: false }">
            
            <!-- MODULE HEADER - CLICKABLE -->
            <div @click="open = !open" 
                 class="px-6 py-5 cursor-pointer hover:bg-gray-50/50 transition-colors duration-150">
                <div class="flex items-center justify-between">
                    
                    <!-- LEFT SECTION -->
                    <div class="flex items-center gap-4 flex-1">
                        <!-- EXPAND ICON -->
                        <div class="transform transition-transform duration-300" :class="{ 'rotate-90': open }">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        <!-- MODULE NUMBER -->
                        <span class="text-sm font-medium text-gray-400 w-8">#{{ ($modules->currentPage() - 1) * $modules->perPage() + $index + 1 }}</span>

                        <!-- MODULE ICON -->
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>

                        <!-- MODULE TITLE & INFO -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $module->title }}</h3>
                                
                                <!-- ORDER BADGE -->
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 rounded-md text-xs font-medium text-gray-700">
                                    Order #{{ $module->order ?: 'N/A' }}
                                </span>
                            </div>
                            <p class="text-xs text-blue-500">
    Course: {{ optional($module->course)->title }}
</p>
                            <!-- MODULE META -->
                            <div class="flex items-center gap-4 mt-1">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                    </svg>
                                    {{ $module->contents->count() }} Contents
                                </span>
                                @if($module->description)
                                <span class="text-xs text-gray-500 truncate max-w-md">{{ Str::limit($module->description, 60) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SECTION - STATUS & ACTIONS -->
                    <div class="flex items-center gap-3">
                        <!-- STATUS -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                            {{ $module->status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 
                                    {{ $module->status ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 
                                    {{ $module->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            </span>
                            {{ $module->status ? 'Active' : 'Inactive' }}
                        </span>

                        <!-- QUICK ACTIONS -->
                        <div class="flex items-center gap-1" @click.stop>
                            <a href="{{ route('modules.edit', $module->_id) }}"
                               class="p-2 hover:bg-amber-50 rounded-lg text-amber-600 transition-colors duration-200 group"
                               title="Edit Module">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="{{ route('modules.delete', $module->_id) }}"
                               onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')"
                               class="p-2 hover:bg-red-50 rounded-lg text-red-600 transition-colors duration-200"
                               title="Delete Module">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODULE CONTENTS - COLLAPSIBLE SECTION -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="border-t border-gray-200 bg-gray-50/30">
                
                <div class="p-6">
                    @if($module->contents->count() > 0)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                    </svg>
                                    Module Contents ({{ $module->contents->count() }})
                                </h4>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($module->contents as $content)
                                <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start gap-4">
                                        
                                        <!-- CONTENT TYPE ICON -->
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0
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

                                        <!-- CONTENT INFO -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h5 class="font-medium text-gray-900">{{ $content->title }}</h5>
                                                    <div class="flex items-center gap-3 mt-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                            {{ $content->type === 'video' ? 'bg-blue-50 text-blue-700' : 
                                                               ($content->type === 'audio' ? 'bg-purple-50 text-purple-700' : 
                                                               'bg-green-50 text-green-700') }}">
                                                            {{ ucfirst($content->type) }}
                                                        </span>
                                                        @if($content->duration)
                                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $content->duration }}
                                                        </span>
                                                        @endif
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium
                                                            {{ $content->status ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                            <span class="relative flex h-1.5 w-1.5">
                                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 
                                                                    {{ $content->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                                            </span>
                                                            {{ $content->status ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- VIEW BUTTON -->
                                                <a href="{{ route('contents.show', $content->_id) }}"
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all duration-200 shadow-sm hover:shadow">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View Content
                                                </a>
                                            </div>
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">No content available in this module</p>
                            <p class="text-gray-400 text-xs mt-1">Click "Add Content" to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <!-- EMPTY STATE -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12">
            <div class="flex flex-col items-center justify-center text-gray-400">
                <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-xl font-medium text-gray-600 mb-2">No modules found</p>
                <p class="text-gray-500 mb-6">Get started by creating your first education module.</p>
                <a href="{{ route('modules.create') }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create First Module
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($modules->hasPages())
    <div class="mt-6">
        {{ $modules->links() }}
    </div>
    @endif

</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    [x-cloak] { display: none !important; }
</style>

@endsection