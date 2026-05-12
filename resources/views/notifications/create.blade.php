@extends('layouts.admin')

@section('title', 'Push Notification')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Push Notifications</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Send instant notifications to your users</p>
                </div>
            </div>
        </div>

        <form action="{{ route('notifications.send') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-lg px-4 py-3 text-emerald-700">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg px-4 py-3 text-red-700">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Send To -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">📢 Send To</label>
                <select name="send_to" id="sendTypeSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="all">🌍 All Users (Send to Everyone)</option>
                    <option value="specific">👥 Specific Users (Select below)</option>
                </select>
            </div>

            <!-- Specific Users Box -->
            <div id="specificUsersBox" class="border-2 border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/30 hidden">
                <label class="block text-sm font-bold text-gray-700 mb-3">👤 Select Specific Users</label>
                
                <!-- Search Box -->
                <div class="relative mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchUser" placeholder="Search users by name or email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                
                <!-- User List -->
                <div id="userList" class="space-y-2 max-h-60 overflow-y-auto bg-white rounded-lg p-2 border border-gray-200">
                    @foreach($users as $user)
                        <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-indigo-50 transition user-item" data-name="{{ strtolower($user->full_name) }} {{ strtolower($user->email) }}">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->_id }}" class="user-checkbox w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-indigo-600">{{ substr($user->full_name, 0, 1) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                
                <!-- Selection Info -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-600">
                        Selected: <span id="selectedCount" class="text-indigo-600 font-bold">0</span> users
                    </div>
                    <button type="button" id="selectAllBtn" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">✓ Select All</button>
                </div>
            </div>

            <!-- Notification Details -->
            <div class="border-t border-gray-200 pt-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">📌 Title</label>
                    <input type="text" name="title" required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        placeholder="e.g., Special Offer Just for You!">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">💬 Message</label>
                    <textarea name="body" required rows="4" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        placeholder="Write your notification message here..."></textarea>
                    <p class="text-xs text-gray-400 mt-1">Max 500 characters</p>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">🖼️ Image (Optional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-400 transition">
                        <input type="file" name="image" accept="image/*" id="imageInput" class="hidden">
                        <label for="imageInput" class="cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-500">Click to upload image</p>
                            <p class="text-xs text-gray-400">JPEG, PNG, JPG (Max 2MB)</p>
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="previewImg" class="h-32 w-auto rounded-lg border shadow-sm" alt="Preview">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-gray-200 pt-6">
                <button type="submit" id="submitBtn" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-3 px-4 rounded-xl transition transform hover:scale-[1.02] flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send Notification
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle specific users box
    const sendTypeSelect = document.getElementById('sendTypeSelect');
    const specificUsersBox = document.getElementById('specificUsersBox');
    
    sendTypeSelect.addEventListener('change', function() {
        if (this.value === 'specific') {
            specificUsersBox.classList.remove('hidden');
        } else {
            specificUsersBox.classList.add('hidden');
        }
    });
    
    // Search functionality
    document.getElementById('searchUser')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.user-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Update selected count
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        document.getElementById('selectedCount').innerText = checkboxes.length;
        const selectAllBtn = document.getElementById('selectAllBtn');
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const allChecked = allCheckboxes.length === checkboxes.length && allCheckboxes.length > 0;
        selectAllBtn.textContent = allChecked ? '✓ Deselect All' : '✓ Select All';
    }
    
    document.querySelectorAll('.user-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
    
    // Select All / Deselect All
    const selectAllBtn = document.getElementById('selectAllBtn');
    selectAllBtn.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        updateSelectedCount();
    });
    
    // Image preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    imageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            imagePreview.classList.add('hidden');
        }
    });
    
    // Submit loading state
    const submitBtn = document.getElementById('submitBtn');
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sending...
        `;
    });
</script>
@endsection