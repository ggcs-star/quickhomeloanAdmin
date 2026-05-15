@extends('layouts.admin')

@section('title', 'Create App Settings')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('app-settings.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Create App Settings</h1>
            </div>
            <p class="text-sm text-gray-500">Configure your application name and logos</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('app-settings.store') }}" method="POST" enctype="multipart/form-data" x-data="appSettingsForm()">
                @csrf

                <!-- 2 COLUMN GRID -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- LEFT COLUMN -->
                        <div class="space-y-5">
                            
                            <!-- App Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Application Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="app_name" 
                                       required 
                                       x-model="appName"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all"
                                       placeholder="Enter application name">
                                <p class="text-xs text-gray-400 mt-1">This name will appear in the app</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="0" class="w-4 h-4 text-gray-400 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Inactive settings won't be applied to the app</p>
                            </div>

                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="space-y-5">
                            
                            <!-- App Logo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">App Logo</label>
                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition cursor-pointer"
                                     @click="$refs.appLogoInput.click()">
                                    <input type="file" name="app_logo" accept="image/*" class="hidden" x-ref="appLogoInput" @change="previewLogo($event, 'app')">
                                    <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP (Max 2MB)</p>
                                </div>
                                <div x-show="appLogoPreview" class="mt-3">
                                    <img :src="appLogoPreview" class="h-20 w-auto rounded-lg border border-gray-200 p-1">
                                </div>
                            </div>

                            <!-- Splash Logo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Splash Logo</label>
                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition cursor-pointer"
                                     @click="$refs.splashLogoInput.click()">
                                    <input type="file" name="splash_logo" accept="image/*" class="hidden" x-ref="splashLogoInput" @change="previewLogo($event, 'splash')">
                                    <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP (Max 2MB)</p>
                                </div>
                                <div x-show="splashLogoPreview" class="mt-3">
                                    <img :src="splashLogoPreview" class="h-20 w-auto rounded-lg border border-gray-200 p-1">
                                </div>
                            </div>

                            <!-- Header Logo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Header Logo</label>
                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition cursor-pointer"
                                     @click="$refs.headerLogoInput.click()">
                                    <input type="file" name="header_logo" accept="image/*" class="hidden" x-ref="headerLogoInput" @change="previewLogo($event, 'header')">
                                    <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP (Max 2MB)</p>
                                </div>
                                <div x-show="headerLogoPreview" class="mt-3">
                                    <img :src="headerLogoPreview" class="h-20 w-auto rounded-lg border border-gray-200 p-1">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('app-settings.index') }}" 
                       class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Create Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function appSettingsForm() {
    return {
        appName: '',
        appLogoPreview: null,
        splashLogoPreview: null,
        headerLogoPreview: null,
        
        previewLogo(event, type) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (type === 'app') this.appLogoPreview = e.target.result;
                    if (type === 'splash') this.splashLogoPreview = e.target.result;
                    if (type === 'header') this.headerLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>
@endsection