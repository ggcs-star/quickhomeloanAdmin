@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Banners</h2>
                <p class="text-gray-500 text-sm mt-1">Manage your website banners</p>
            </div>
            <a href="{{ route('banners.create') }}"
                class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Banner
            </a>
        </div>

        <!-- Banner Grid -->
        @if($banners->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($banners as $banner)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition duration-200 overflow-hidden group">
                        <!-- Image Container -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 right-3">
                                <span class="bg-gray-900/75 text-white text-xs px-2.5 py-1 rounded-full">
                                    Order: {{ $banner->order ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 text-lg mb-2 truncate">{{ $banner->title }}</h3>

                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-700 text-sm inline-flex items-center mb-3">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    {{ Str::limit($banner->link, 40) }}
                                </a>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('banners.edit', $banner->_id) }}"
                                    class="flex-1 inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('banners.delete', $banner->_id) }}" class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No banners yet</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first banner.</p>
                <a href="{{ route('banners.create') }}"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Banner
                </a>
            </div>
        @endif
    </div>
@endsection