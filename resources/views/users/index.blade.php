@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and monitor all registered users</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" 
                    class="p-2.5 hover:bg-gray-100 rounded-xl transition-colors duration-200"
                    title="Refresh">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-emerald-700 font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                    <p class="text-sm text-gray-500">Total Users</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->where('status', 'active')->count() }}</p>
                    <p class="text-sm text-gray-500">Active Users</p>
                </div>
            </div>
        </div>

        <!-- Inactive Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->where('status', '!=', 'active')->count() }}</p>
                    <p class="text-sm text-gray-500">Inactive Users</p>
                </div>
            </div>
        </div>

        <!-- New This Month -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}
                    </p>
                    <p class="text-sm text-gray-500">New This Month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" 
                       placeholder="Search by name, email, or mobile..."
                       class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                       onkeyup="filterUsers()">
            </div>
            <select id="statusFilter" onchange="filterUsers()"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white">
                <option value="all">📊 All Users</option>
                <option value="active">✅ Active</option>
                <option value="inactive">⏸️ Inactive</option>
            </select>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="p-4 text-left font-semibold text-gray-600 w-16">
                            <span class="flex items-center gap-2">#</span>
                        </th>
                        <th class="p-4 text-left font-semibold text-gray-600">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                User Details
                            </span>
                        </th>
                        <th class="p-4 text-left font-semibold text-gray-600">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email
                            </span>
                        </th>
                        <th class="p-4 text-left font-semibold text-gray-600">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Mobile
                            </span>
                        </th>
                        <th class="p-4 text-center font-semibold text-gray-600 w-32">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Status
                            </span>
                        </th>
                        <th class="p-4 text-center font-semibold text-gray-600 w-32">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Action
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="user-row hover:bg-gray-50/50 transition-colors duration-200"
                            data-name="{{ strtolower($user->full_name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-mobile="{{ strtolower($user->mobile_number) }}"
                            data-status="{{ $user->status == 'active' ? 'active' : 'inactive' }}">
                            
                            <!-- Serial Number -->
                            <td class="p-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-700 font-medium text-xs">
                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                </span>
                            </td>

                            <!-- User Info -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($user->full_name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $user->full_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            Registered {{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 truncate max-w-[200px]">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 flex-shrink-0">
                                            Verified
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Mobile -->
                            <td class="p-4">
                                <span class="text-gray-600">{{ $user->mobile_number ?: 'Not provided' }}</span>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <div class="flex justify-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
    @if($user->status == 'active')
        bg-emerald-50 text-emerald-700
    @elseif($user->status == 'inactive')
        bg-yellow-50 text-yellow-700
    @else
        bg-red-50 text-red-700
    @endif">

    <span class="w-1.5 h-1.5 rounded-full
        @if($user->status == 'active')
            bg-emerald-500
        @elseif($user->status == 'inactive')
            bg-yellow-500
        @else
            bg-red-500
        @endif">
    </span>

    {{ ucfirst($user->status) }}
</span>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="p-4">
                                <div class="flex justify-center">
                                  <form method="POST" action="{{ route('users.toggle', $user->_id) }}">
    @csrf

    <select name="status"
        onchange="this.form.submit()"
        class="px-3 py-1.5 text-xs rounded-lg border focus:ring-2 focus:ring-blue-500
        @if($user->status == 'active')
            bg-emerald-50 text-emerald-700 border-emerald-300
        @elseif($user->status == 'inactive')
            bg-yellow-50 text-yellow-700 border-yellow-300
        @else
            bg-red-50 text-red-700 border-red-300
        @endif
        ">
 <option value="" {{ $user->status == '' ? 'selected' : '' }}>
             select status
        </option>
        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>
             Active
        </option>

        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
             Inactive
        </option>

        <option value="ban" {{ $user->status == 'ban' ? 'selected' : '' }}>
             Banned
        </option>

    </select>
</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-16">
                                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-6 text-xl font-semibold text-gray-900">No Users Found</h3>
                                    <p class="mt-2 text-gray-500 max-w-sm mx-auto">
                                        @if(request()->has('search') || request()->has('status'))
                                        No users match your current filters. Try adjusting your search criteria.
                                        @else
                                        There are no registered users in the system yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium">{{ $users->total() }}</span> users
                    </p>
                    <div class="pagination-links">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom pagination styling */
    .pagination-links nav {
        display: flex;
        gap: 4px;
    }
    .pagination-links .relative {
        display: flex;
        gap: 4px;
    }
    .pagination-links span,
    .pagination-links a {
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
    }
    .pagination-links span[aria-current="page"] {
        background-color: #3B82F6;
        color: white;
        font-weight: 600;
    }
    .pagination-links a {
        color: #6B7280;
        background-color: white;
        border: 1px solid #E5E7EB;
    }
    .pagination-links a:hover {
        background-color: #F3F4F6;
        border-color: #D1D5DB;
    }
    .pagination-links .cursor-default {
        color: #D1D5DB;
        background-color: #F9FAFB;
        pointer-events: none;
    }
</style>

<script>
    function filterUsers() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const mobile = row.dataset.mobile || '';
            const status = row.dataset.status;
            
            const matchesSearch = name.includes(searchTerm) || 
                                email.includes(searchTerm) || 
                                mobile.includes(searchTerm);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                row.style.opacity = '1';
            } else {
                row.style.display = 'none';
                row.style.opacity = '0';
            }
        });
    }
</script>
@endsection