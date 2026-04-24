@extends('layouts.admin')

@section('title', 'Calculators')

@section('content')
    <div class="p-6">

        <!-- Success Message -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 p-3 bg-green-100 text-green-700 rounded flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 space-y-4">
            <!-- Search & Dropdown Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" id="searchInput" placeholder="Search calculators..." 
                    class="flex-1 p-2 border rounded-lg"
                    onkeyup="filterCalculators()">

                <select id="statusFilter" class="p-2 border rounded-lg" onchange="filterCalculators()">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <select id="accessFilter" class="p-2 border rounded-lg" onchange="filterCalculators()">
                    <option value="all">All Access</option>
                    <option value="free">Free</option>
                    <option value="premium">Premium</option>
                </select>
            </div>

            <!-- User Type Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <span class="text-sm font-medium text-gray-700 self-center mr-2">User Type:</span>
                <button onclick="setUserTypeFilter('all')" 
                    id="filter-all"
                    class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                           bg-blue-600 text-white border-blue-600 hover:bg-blue-700">
                    All Users
                </button>
                <button onclick="setUserTypeFilter('first_time')" 
                    id="filter-first_time"
                    class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                           bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600">
                    First Time
                </button>
                <button onclick="setUserTypeFilter('existing')" 
                    id="filter-existing"
                    class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                           bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600">
                    Existing
                </button>
            </div>
        </div>

        <!-- Calculator Grid -->
        @if($calculators->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No calculators</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new calculator.</p>
                <div class="mt-6">
                    <a href="{{ route('calculators.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Calculator
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="calculatorsGrid">
                @foreach($calculators as $calc)
                    <div class="calculator-card bg-white shadow-md rounded-xl p-5 border hover:shadow-lg transition"
                        data-name="{{ strtolower($calc->name) }}" 
                        data-status="{{ $calc->is_active ? 'active' : 'inactive' }}"
                        data-access="{{ $calc->access_type }}"
                        data-user-type="{{ $calc->user_type }}">

                        <!-- Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                       {{ $calc->access_type === 'premium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($calc->access_type) }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                       {{ $calc->user_type === 'first_time' ? 'bg-purple-100 text-purple-800' : 
                                          ($calc->user_type === 'existing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $calc->user_type === 'first_time' ? 'First Time' : 
                                   ($calc->user_type === 'existing' ? 'Existing' : 'All Users') }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold mb-1">{{ $calc->name }}</h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($calc->description, 100) }}</p>

                        <!-- Category -->
                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ $calc->category }}
                        </div>

                        <!-- Action Buttons (Status Toggle + User Type Update) -->
                        <div class="space-y-3 pt-3 border-t">
                            <!-- Status Toggle -->
                            <form method="POST" action="{{ route('calculators.toggle', $calc->_id) }}" class="flex items-center justify-between">
                                @csrf
                                @method('POST')
                                <span class="text-sm text-gray-600">Status</span>
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to {{ $calc->is_active ? 'deactivate' : 'activate' }} this calculator?')"
                                    class="relative inline-flex items-center h-6 rounded-full w-11 transition
                                           {{ $calc->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition
                                               {{ $calc->is_active ? 'translate-x-6' : 'translate-x-1' }}">
                                    </span>
                                </button>
                            </form>

                            <!-- User Type Quick Update -->
                            <form method="POST" action="{{ route('calculators.updateUserType', $calc->_id) }}">
                                @csrf
                                @method('POST')
                                <label class="block text-xs text-gray-500 mb-1.5">User Type</label>
                                <div class="flex gap-1">
                                    <button type="submit" name="user_type" value="all"
                                        class="flex-1 px-2 py-1 text-xs rounded border transition
                                               {{ $calc->user_type == 'all' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
                                        All
                                    </button>
                                    <button type="submit" name="user_type" value="first_time"
                                        class="flex-1 px-2 py-1 text-xs rounded border transition
                                               {{ $calc->user_type == 'first_time' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-600 border-gray-300 hover:border-purple-400' }}">
                                        First
                                    </button>
                                    <button type="submit" name="user_type" value="existing"
                                        class="flex-1 px-2 py-1 text-xs rounded border transition
                                               {{ $calc->user_type == 'existing' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
                                        Existing
                                    </button>
                                </div>
                            </form>

                            <!-- Access Type Quick Update -->
                            <form method="POST" action="{{ route('calculators.updateAccess', $calc->_id) }}">
                                @csrf
                                @method('POST')
                                <label class="block text-xs text-gray-500 mb-1.5">Access Type</label>
                                <div class="flex gap-1">
                                    <button type="submit" name="access_type" value="free"
                                        class="flex-1 px-2 py-1 text-xs rounded border transition
                                               {{ $calc->access_type == 'free' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-300 hover:border-green-400' }}">
                                        Free
                                    </button>
                                    <button type="submit" name="access_type" value="premium"
                                        class="flex-1 px-2 py-1 text-xs rounded border transition
                                               {{ $calc->access_type == 'premium' ? 'bg-yellow-600 text-white border-yellow-600' : 'bg-white text-gray-600 border-gray-300 hover:border-yellow-400' }}">
                                        Premium
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($calculators instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">
                    {{ $calculators->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        let currentUserTypeFilter = 'all';

        function setUserTypeFilter(userType) {
            currentUserTypeFilter = userType;
            
            // Update button styles
            document.querySelectorAll('.user-type-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });
            
            const activeBtn = document.getElementById('filter-' + userType);
            activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            
            filterCalculators();
        }

        function filterCalculators() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const accessFilter = document.getElementById('accessFilter').value;

            document.querySelectorAll('.calculator-card').forEach(card => {
                const name = card.dataset.name;
                const status = card.dataset.status;
                const access = card.dataset.access;
                const userType = card.dataset.userType;

                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                const matchesAccess = accessFilter === 'all' || access === accessFilter;
                const matchesUserType = currentUserTypeFilter === 'all' || userType === currentUserTypeFilter;

                card.style.display = matchesSearch && matchesStatus && matchesAccess && matchesUserType ? '' : 'none';
            });
        }
    </script>
@endsection