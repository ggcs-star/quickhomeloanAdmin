@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Courses</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your course catalog</p>
        </div>
        <a href="{{ route('courses.create') }}"
           class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Course
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-700 font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Courses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $courses->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Active Courses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $courses->where('status', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Inactive Courses</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $courses->where('status', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search courses by title..." 
                       class="pl-10 p-2.5 border rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="filterCourses()">
            </div>
            <select id="statusFilter" onchange="filterCourses()"
                    class="p-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl shadow-sm border">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="mt-4 text-xl font-semibold text-gray-900">No Courses Yet</h3>
            <p class="mt-2 text-gray-500">Start creating your first course to get started.</p>
            <div class="mt-6">
                <a href="{{ route('courses.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create First Course
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="coursesGrid">
            @foreach($courses as $course)
                <div class="course-card bg-white rounded-xl shadow-sm border hover:shadow-lg transition-all duration-300 overflow-hidden group"
                     data-title="{{ strtolower($course->title) }}"
                     data-status="{{ $course->status ? 'active' : 'inactive' }}">
                    
                    <!-- Course Image -->
                    <div class="relative overflow-hidden bg-gray-100">
                        @if($course->image)
                            <img src="{{ asset('storage/'.$course->image) }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm
                                       {{ $course->status ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                                {{ $course->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $course->title }}</h3>
                        
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ $course->description ?: 'No description available' }}
                        </p>

                        <div class="flex items-center text-xs text-gray-400 mb-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            {{ $course->slug }}
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t">
                            <form method="POST" action="{{ route('courses.toggle', $course->_id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to {{ $course->status ? 'deactivate' : 'activate' }} this course?')"
                                        class="relative inline-flex items-center h-7 rounded-full w-12 transition
                                               {{ $course->status ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="inline-block w-5 h-5 transform bg-white rounded-full transition shadow
                                               {{ $course->status ? 'translate-x-6' : 'translate-x-1' }}">
                                    </span>
                                </button>
                            </form>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('courses.edit', $course->_id) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit Course">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('courses.delete', $course->_id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                    @csrf
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete Course">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function filterCourses() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        document.querySelectorAll('.course-card').forEach(card => {
            const title = card.dataset.title;
            const status = card.dataset.status;
            
            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;
            
            card.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }
</script>
@endsection