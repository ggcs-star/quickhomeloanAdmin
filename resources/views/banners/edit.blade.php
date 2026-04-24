@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Edit Banner</h2>
            <p class="text-gray-500 text-sm mt-1">Update the banner details</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('banners.update', $banner->_id) }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Banner Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Image</label>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                            class="w-48 h-32 object-cover rounded-lg border border-gray-200">
                        <span
                            class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">Active</span>
                    </div>
                </div>

                <!-- New Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Change Image
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition duration-200 cursor-pointer"
                        onclick="document.getElementById('image').click()">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden"
                            onchange="previewImage(this)">
                        <div id="upload-placeholder">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-600">
                                <span class="text-blue-600 font-medium">Click to upload new image</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Leave empty to keep the current image</p>
                        </div>
                        <img id="image-preview" class="max-h-48 mx-auto hidden rounded-lg">
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link -->
                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Banner Link
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <input type="text" name="link" id="link" placeholder="https://example.com"
                            value="{{ old('link', $banner->link) }}"
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Display Order
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers will be displayed first</p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Update Banner
                    </button>
                    <a href="{{ route('banners.index') }}"
                        class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection