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

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- TABLE HEADER STATS -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Total Modules:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $modules->count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Active:</span>
                    <span class="text-sm font-semibold text-emerald-600">{{ $modules->where('status', true)->count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Inactive:</span>
                    <span class="text-sm font-semibold text-gray-400">{{ $modules->where('status', false)->count() }}</span>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                
                <!-- TABLE HEAD -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Module Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Display Order</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- TABLE BODY -->
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($modules as $key => $module)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        
                        <!-- SERIAL NUMBER -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500 font-medium">{{ $key + 1 }}</span>
                        </td>

                        <!-- TITLE -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $module->title }}</span>
                            </div>
                        </td>

                        <!-- ORDER -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 rounded-md text-xs font-medium text-gray-700">
                                Order #{{ $module->order ?: 'N/A' }}
                            </span>
                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                
                                <!-- Edit Button -->
                                <a href="{{ route('modules.edit', $module->_id) }}"
                                   class="group relative inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-medium transition-all duration-200 border border-amber-200/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <!-- Delete Button -->
                                <a href="{{ route('modules.delete', $module->_id) }}"
                                   onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')"
                                   class="group relative inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-medium transition-all duration-200 border border-red-200/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Delete</span>
                                </a>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-base font-medium">No modules found</p>
                                <p class="text-sm mt-1">Get started by creating your first education module.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>

@endsection