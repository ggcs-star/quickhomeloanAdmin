@extends('layouts.admin')
@section('title', 'Education Contents')

@section('content')

<div class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Education Contents</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your learning materials and resources</p>
        </div>

        <a href="{{ route('contents.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Content
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
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Total Contents:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $contents->total() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Active:</span>
                    <span class="text-sm font-semibold text-emerald-600">{{ $contents->where('status', true)->count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">By Type:</span>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 text-xs">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            <span>Video: {{ $contents->where('type', 'video')->count() }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs">
                            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                            <span>Audio: {{ $contents->where('type', 'audio')->count() }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span>Text: {{ $contents->where('type', 'text')->count() }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                
                <!-- TABLE HEAD -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Content Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Thumbnail</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- TABLE BODY -->
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contents as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        
                        <!-- TITLE -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                    {{ $item->type === 'video' ? 'bg-blue-100' : ($item->type === 'audio' ? 'bg-purple-100' : 'bg-green-100') }}">
                                    @if($item->type === 'video')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    @elseif($item->type === 'audio')
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $item->title }}</span>
                            </div>
                        </td>

                        <!-- MODULE -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $item->module->title ?? '—' }}</span>
                        </td>

                        <!-- TYPE -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $item->type === 'video' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20' : 
                                   ($item->type === 'audio' ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-600/20' : 
                                   'bg-green-50 text-green-700 ring-1 ring-green-600/20') }}">
                                {{ ucfirst($item->type) }}
                            </span>
                        </td>

                        <!-- THUMBNAIL -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->thumbnail)
                                <div class="relative group">
                                    <img src="{{ asset('storage/'.$item->thumbnail) }}" 
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm">
                                    <div class="absolute inset-0 bg-black/40 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        <!-- ORDER -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 rounded-md text-xs font-medium text-gray-700">
                                #{{ $item->order ?: 'N/A' }}
                            </span>
                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                {{ $item->status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 
                                        {{ $item->status ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 
                                        {{ $item->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                </span>
                                {{ $item->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                
                                <!-- View Button -->
                                <a href="{{ route('contents.show', $item->_id) }}"
                                   class="group relative inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-medium transition-all duration-200 border border-blue-200/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>View</span>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('contents.edit', $item->_id) }}"
                                   class="group relative inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-medium transition-all duration-200 border border-amber-200/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <!-- Delete Button -->
                                <a href="{{ route('contents.delete', $item->_id) }}"
                                   onclick="return confirm('Are you sure you want to delete this content? This action cannot be undone.')"
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
                        <td colspan="7" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-base font-medium">No content found</p>
                                <p class="text-sm mt-1">Get started by adding your first educational content.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        @if($contents->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $contents->links() }}
        </div>
        @endif
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