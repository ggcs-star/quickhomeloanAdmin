@extends('layouts.admin')

@section('title', 'Reels Management')

@section('content')
    <div class="p-6 max-w-7xl mx-auto"
        x-data="{ showCommentsModal: false, selectedReelComments: [], selectedReelTitle: '' }">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Reels Management</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your video reels, track engagement, and control display
                    settings</p>
            </div>
            <a href="{{ route('reels.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Reel
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="bg-green-50 border border-green-200 text-green-800 p-4 mb-6 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Stats Overview Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            {{-- Total Reels --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Reels</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $reels->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active Reels --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Reels</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $reels->where('is_active', true)->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Views --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Views</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $reels->sum('views') }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Likes --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Likes</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $reels->sum('likes_count') }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Comments --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Comments</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $reels->sum('comments_count') }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            {{-- Table Header with Pagination Info --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        Showing
                        <span class="font-semibold text-gray-900">{{ $reels->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-semibold text-gray-900">{{ $reels->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-semibold text-gray-900">{{ $reels->total() }}</span>
                        reels
                    </span>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Reel Details</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Video Preview</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Views</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Likes</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Comments</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Order</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Status</th>
                            <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider py-4 px-4">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($reels as $reel)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                {{-- Reel Details --}}
                                <td class="py-4 px-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $reel->title }}</div>
                                        @if($reel->description)
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $reel->description }}</p>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">ID: {{ $reel->_id }}</div>
                                    </div>
                                </td>

                                {{-- Video Preview --}}
                                <td class="py-4 px-4">
                                    <div class="relative group">
                                        <video width="120" class="rounded-lg border border-gray-200 shadow-sm">
                                            <source src="{{ asset('storage/' . $reel->file) }}">
                                        </video>
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 rounded-lg pointer-events-none">
                                        </div>
                                    </div>
                                </td>

                                {{-- Views --}}
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ number_format($reel->views ?? 0) }}</span>
                                    </div>
                                </td>

                                {{-- Likes --}}
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ number_format($reel->likes_count) }}</span>
                                    </div>
                                </td>

                                {{-- Comments Count with Eye Button --}}
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ number_format($reel->comments_count) }}
                                        </span>
                                        @if($reel->comments_count > 0)
                                            <button @click="
                                                showCommentsModal = true; 
                                                selectedReelComments = {{ Js::from($reel->latest_comments) }};
                                                selectedReelTitle = '{{ $reel->title }}';
                                            " 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg transition-colors duration-200"
                                            title="View all comments">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-50 text-gray-300 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Order --}}
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-50 text-indigo-700 text-sm font-semibold">
                                        {{ $reel->order }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-4 text-center">
                                    @if($reel->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('reels.edit', $reel->_id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition-colors duration-200"
                                            title="Edit reel">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('reels.delete', $reel->_id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this reel?\n\nTitle: {{ $reel->title }}\nViews: {{ $reel->views }}\nLikes: {{ $reel->likes_count }}\n\nThis action cannot be undone.');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition-colors duration-200"
                                                title="Delete reel">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No reels found</h3>
                                        <p class="text-sm text-gray-500 mb-4">Get started by creating your first video reel</p>
                                        <a href="{{ route('reels.create') }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Create Your First Reel
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($reels->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing
                        <span class="font-medium">{{ $reels->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $reels->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $reels->total() }}</span>
                        results
                    </div>
                    <div>
                        {{ $reels->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Comments Modal --}}
        <div x-show="showCommentsModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            @click.self="showCommentsModal = false">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            {{-- Modal Content --}}
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                            <p class="text-sm text-gray-500 mt-0.5" x-text="'Reel: ' + selectedReelTitle"></p>
                        </div>
                        <button @click="showCommentsModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        <template x-if="selectedReelComments && selectedReelComments.length > 0">
                            <div class="space-y-4">
                                <template x-for="(comment, index) in selectedReelComments" :key="index">
                                    <div
                                        class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-gray-300 transition-colors">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                   
                                                    <p class="text-xs text-gray-500"
                                                        x-text="comment.created_at ? new Date(comment.created_at).toLocaleString() : ''">
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400" x-text="'#' + (index + 1)"></span>
                                        </div>
                                        <p class="text-sm text-gray-700 ml-11" x-text="comment.comment"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!selectedReelComments || selectedReelComments.length === 0">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="text-gray-500 text-sm">No comments available</p>
                            </div>
                        </template>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            Total: <span class="font-semibold"
                                x-text="selectedReelComments ? selectedReelComments.length : 0"></span> comments
                        </p>
                        <button @click="showCommentsModal = false"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js for interactive features --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection