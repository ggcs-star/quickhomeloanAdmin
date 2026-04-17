@extends('layouts.admin')
@section('title', 'View Content')

@section('content')

<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="flex items-center gap-4">
        <a href="{{ route('contents.index') }}" 
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200 group">
            <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Details</h1>
            <p class="text-sm text-gray-500 mt-0.5">View and manage your educational content</p>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN - MEDIA PLAYER -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- MEDIA PLAYER CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- MEDIA HEADER -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-transparent">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            {{ $content->type === 'video' ? 'bg-blue-100' : ($content->type === 'audio' ? 'bg-purple-100' : 'bg-green-100') }}">
                            @if($content->type === 'video')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            @elseif($content->type === 'audio')
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $content->title }}</h2>
                            <p class="text-xs text-gray-500">{{ ucfirst($content->type) }} Content</p>
                        </div>
                    </div>
                </div>

                <!-- MEDIA DISPLAY AREA -->
                <div class="p-6">
                    @if($content->file)
                        
                        {{-- VIDEO PLAYER --}}
                        @if($content->type === 'video')
                            <div class="relative group rounded-xl overflow-hidden bg-black">
                                <video controls class="w-full aspect-video" poster="{{ $content->thumbnail ? asset('storage/'.$content->thumbnail) : '' }}">
                                    <source src="{{ asset('storage/'.$content->file) }}">
                                    Your browser does not support the video element.
                                </video>
                                
                                <!-- Video Overlay Controls -->
                                <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                </div>
                            </div>

                        {{-- AUDIO PLAYER --}}
                        @elseif($content->type === 'audio')
                            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-8">
                                <!-- Audio Artwork -->
                                <div class="flex justify-center mb-6">
                                    @if($content->thumbnail)
                                        <img src="{{ asset('storage/'.$content->thumbnail) }}" 
                                             class="w-48 h-48 rounded-2xl shadow-lg object-cover border-4 border-white">
                                    @else
                                        <div class="w-48 h-48 rounded-2xl shadow-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                            <svg class="w-24 h-24 text-white/80" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Audio Controls -->
                                <div class="bg-white rounded-xl p-6 shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-4">{{ $content->title }}</h3>
                                    <audio controls class="w-full">
                                        <source src="{{ asset('storage/'.$content->file) }}">
                                        Your browser does not support the audio element.
                                    </audio>
                                    
                                    <!-- Audio Wave Animation -->
                                    <div class="flex items-center justify-center gap-1 mt-6">
                                        @for($i = 1; $i <= 20; $i++)
                                            <div class="w-1 bg-gradient-to-t from-purple-500 to-indigo-600 rounded-full animate-pulse" 
                                                 style="height: {{ rand(10, 40) }}px; animation-delay: {{ $i * 0.1 }}s;"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                        {{-- TEXT/DOCUMENT VIEWER --}}
                        @else
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-8">
                                <div class="max-w-2xl mx-auto text-center">
                                    <!-- Document Icon -->
                                    <div class="mb-6">
                                        <div class="w-32 h-32 mx-auto bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Document Info -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $content->title }}</h3>
                                    <p class="text-gray-600 mb-6">Document Content</p>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="{{ asset('storage/'.$content->file) }}" target="_blank"
                                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Document
                                        </a>
                                        <a href="{{ asset('storage/'.$content->file) }}" download
                                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-all duration-200 border border-gray-300 shadow-sm hover:shadow">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @else
                        <!-- No Media Uploaded -->
                        <div class="bg-gray-50 rounded-xl p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Media File</h3>
                            <p class="text-gray-500">No media file has been uploaded for this content yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- DESCRIPTION CARD -->
            @if($content->description)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Description
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $content->description }}</p>
                </div>
            </div>
            @endif

        </div>

        <!-- RIGHT COLUMN - INFO SIDEBAR -->
        <div class="space-y-6">
            
            <!-- CONTENT INFO CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Content Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    
                    <!-- Module Info -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Module</p>
                            <p class="text-sm font-medium text-gray-900">{{ $content->module->title ?? '—' }}</p>
                        </div>
                    </div>

                    <!-- Type Info -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ $content->type === 'video' ? 'bg-blue-100' : ($content->type === 'audio' ? 'bg-purple-100' : 'bg-green-100') }}">
                            @if($content->type === 'video')
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            @elseif($content->type === 'audio')
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Content Type</p>
                            <span class="inline-flex items-center px-2.5 py-1 mt-1 rounded-full text-xs font-medium
                                {{ $content->type === 'video' ? 'bg-blue-50 text-blue-700' : 
                                   ($content->type === 'audio' ? 'bg-purple-50 text-purple-700' : 
                                   'bg-green-50 text-green-700') }}">
                                {{ ucfirst($content->type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Duration</p>
                            <p class="text-sm font-medium text-gray-900">{{ $content->duration ?: 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Display Order</p>
                            <p class="text-sm font-medium text-gray-900">#{{ $content->order ?: 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 mt-1 rounded-full text-xs font-semibold
                                {{ $content->status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-1 ring-red-600/20' }}">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 
                                        {{ $content->status ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 
                                        {{ $content->status ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                </span>
                                {{ $content->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

         

            <!-- METADATA CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Metadata
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Created</p>
                        <p class="text-sm text-gray-900">{{ $content->created_at ? $content->created_at->format('M d, Y h:i A') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $content->updated_at ? $content->updated_at->format('M d, Y h:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('contents.edit', $content->_id) }}"
                       class="flex items-center gap-3 w-full px-4 py-3 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-sm font-medium transition-all duration-200 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Content
                    </a>
                    
                   
                    
                    <a href="{{ route('contents.delete', $content->_id) }}"
                       onclick="return confirm('Are you sure you want to delete this content? This action cannot be undone.')"
                       class="flex items-center gap-3 w-full px-4 py-3 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition-all duration-200 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Content
                    </a>
                </div>
            </div>

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
    
    @keyframes wave {
        0%, 100% { transform: scaleY(1); }
        50% { transform: scaleY(0.5); }
    }
    
    audio::-webkit-media-controls-panel {
        background-color: white;
    }
    
    video::-webkit-media-controls-panel {
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
    }
</style>

@endsection