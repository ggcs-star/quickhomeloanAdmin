@extends('layouts.admin')
@section('title', 'Add Content')

@section('content')

    <div class="p-6 space-y-6 max-w-4xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="flex items-center gap-4">
            <a href="{{ route('contents.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Content</h1>
                <p class="text-sm text-gray-500 mt-0.5">Create and upload educational content</p>
            </div>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- FORM HEADER -->
            <div class="px-8 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50/50 to-transparent">
                <h2 class="text-lg font-semibold text-gray-900">Content Information</h2>
                <p class="text-sm text-gray-500 mt-1">Fill in the details for the new content item</p>
            </div>

            <!-- VALIDATION ERRORS -->
            @if($errors->any())
                <div class="mx-8 mt-6 p-4 rounded-xl bg-red-50 border border-red-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm text-red-700">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- FORM BODY -->
            <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <div class="space-y-6">

                    <!-- MODULE SELECTION -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Select Module
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="module_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                            <option value="">— Choose a module —</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->_id }}">{{ $module->title }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500">Select which module this content belongs to</p>
                    </div>

                    <!-- TITLE FIELD -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M3 14h18M3 18h18M3 6h18" />
                            </svg>
                            Content Title
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" required placeholder="e.g., Introduction to Variables"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- CONTENT TYPE -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            Content Type
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 cursor-pointer">
                            <option value="video">🎬 Video</option>
                            <option value="audio">🎵 Audio</option>
                            <option value="text">📄 Text</option>
                        </select>
                    </div>

                    <!-- TWO COLUMN LAYOUT -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- FILE UPLOAD -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload File
                            </label>
                            <input type="file" name="file"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500">Upload video, audio, or document file</p>
                        </div>

                        <!-- THUMBNAIL UPLOAD -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Thumbnail Image
                            </label>
                            <input type="file" name="thumbnail" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500">Optional preview image for the content</p>
                        </div>

                    </div>

                    <!-- TWO COLUMN LAYOUT -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- DURATION -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Duration
                            </label>
                            <input type="text" name="duration" placeholder="e.g., 5:30"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <p class="text-xs text-gray-500">Length in minutes:seconds format</p>
                        </div>

                        <!-- ORDER -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                Display Order
                            </label>
                            <input type="number" name="order" min="0" placeholder="e.g., 1"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <p class="text-xs text-gray-500">Lower numbers appear first</p>
                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Description
                        </label>
                        <textarea name="description" rows="4" placeholder="Provide a brief description of this content..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
                    </div>
                    <div class="space-y-3">
                        <label class="text-sm font-medium text-gray-700">FAQs</label>

                        <div id="faq-wrapper">
                            <div class="faq-item grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                                <input type="text" name="faqs[0][title]" placeholder="FAQ Title"
                                    class="w-full px-3 py-2 border rounded">

                                <input type="text" name="faqs[0][description]" placeholder="FAQ Description"
                                    class="w-full px-3 py-2 border rounded">
                            </div>
                        </div>

                        <button type="button" onclick="addFaq()" class="px-3 py-1 bg-indigo-500 text-white rounded text-sm">
                            + Add FAQ
                        </button>
                    </div>

                    <!-- STATUS -->
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
                            <option value="1">✅ Active - Visible to users</option>
                            <option value="0">⏸️ Inactive - Hidden from users</option>
                        </select>
                    </div>

                </div>

                <!-- FORM ACTIONS -->
                <div class="flex items-center gap-3 pt-8 mt-2 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create Content
                    </button>

                    <a href="{{ route('contents.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>
    <script>
        let faqIndex = 1;

        function addFaq() {
            const wrapper = document.getElementById('faq-wrapper');

            const div = document.createElement('div');
            div.classList.add('faq-item', 'grid', 'grid-cols-1', 'sm:grid-cols-2', 'gap-3', 'mb-2');

            div.innerHTML = `
            <input type="text" name="faqs[${faqIndex}][title]" placeholder="FAQ Title"
                class="w-full px-3 py-2 border rounded">

            <div class="flex gap-2">
                <input type="text" name="faqs[${faqIndex}][description]" placeholder="FAQ Description"
                    class="w-full px-3 py-2 border rounded">

                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="bg-red-500 text-white px-2 rounded">X</button>
            </div>
        `;

            wrapper.appendChild(div);
            faqIndex++;
        }
    </script>
@endsection