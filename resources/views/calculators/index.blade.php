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
                <input type="text" id="searchInput" placeholder="Search calculators..." class="flex-1 p-2 border rounded-lg"
                    onkeyup="filterCalculators()">

                <select id="statusFilter" class="p-2 border rounded-lg" onchange="filterCalculators()">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <select id="accessFilter" class="p-2 border rounded-lg" onchange="filterCalculators()">
                    <option value="all">All Access Types</option>
                    <option value="free">Free</option>
                    <option value="premium">Premium</option>
                </select>
            </div>

            <!-- User Type Filter Buttons (Multiple Selection) -->
            <div class="flex flex-wrap gap-2">
                <span class="text-sm font-medium text-gray-700 self-center mr-2">User Type:</span>
                <button onclick="toggleUserTypeFilter('all')" id="filter-all" class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                       bg-blue-600 text-white border-blue-600 hover:bg-blue-700">
                    All
                </button>
                <button onclick="toggleUserTypeFilter('free')" id="filter-free" class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                       bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600">
                    Free
                </button>
                <button onclick="toggleUserTypeFilter('first_time')" id="filter-first_time" class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                       bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600">
                    First Time
                </button>
                <button onclick="toggleUserTypeFilter('existing')" id="filter-existing" class="user-type-btn px-4 py-1.5 text-sm rounded-full border-2 transition
                       bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600">
                    Existing
                </button>

                <!-- Active Filters Display -->
                <span id="activeFilters" class="text-sm text-blue-600 font-medium self-center ml-4"></span>
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

            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="calculatorsGrid">
                @foreach($calculators as $calc)
                    <div class="calculator-card bg-white shadow-md rounded-xl p-5 border hover:shadow-lg transition"
                        data-name="{{ strtolower($calc->name) }}" data-status="{{ $calc->is_active ? 'active' : 'inactive' }}"
                        data-access="{{ $calc->access_type }}" data-user-type="{{ $calc->user_type }}">

                        <!-- Badge -->
                        <div class="flex items-center justify-between mb-3">

                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
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
                            <form method="POST" action="{{ route('calculators.toggle', $calc->_id) }}"
                                class="flex items-center justify-between">
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
        // Store selected filters (multiple selection)
        let selectedUserTypes = new Set(['all']);

        function toggleUserTypeFilter(userType) {
            // If "All" is clicked, reset to only "all"
            if (userType === 'all') {
                selectedUserTypes.clear();
                selectedUserTypes.add('all');
            } else {
                // Remove "all" if it was previously selected
                selectedUserTypes.delete('all');

                // Toggle the clicked filter
                if (selectedUserTypes.has(userType)) {
                    selectedUserTypes.delete(userType);
                } else {
                    selectedUserTypes.add(userType);
                }

                // If no filters selected, revert to "all"
                if (selectedUserTypes.size === 0) {
                    selectedUserTypes.add('all');
                }
            }

            updateButtonStyles();
            filterCalculators();
        }

        function updateButtonStyles() {
            const allButtons = {
                'all': document.getElementById('filter-all'),
                'free': document.getElementById('filter-free'),
                'first_time': document.getElementById('filter-first_time'),
                'existing': document.getElementById('filter-existing')
            };

            // Reset all buttons to default style
            Object.values(allButtons).forEach(btn => {
                if (btn) {
                    btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                }
            });

            // Highlight selected buttons
            selectedUserTypes.forEach(type => {
                if (allButtons[type]) {
                    allButtons[type].classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    allButtons[type].classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                }
            });

            // Update active filters display
            const activeFiltersSpan = document.getElementById('activeFilters');
            if (selectedUserTypes.has('all')) {
                activeFiltersSpan.textContent = '';
            } else {
                const filterNames = Array.from(selectedUserTypes).map(type => {
                    return type === 'free' ? 'Free' :
                        type === 'first_time' ? 'First Time' :
                            type === 'existing' ? 'Existing' : type;
                });
                activeFiltersSpan.textContent = `Showing: ${filterNames.join(' + ')}`;
            }
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

                // Check if user type matches any of the selected filters
                const matchesUserType = selectedUserTypes.has('all') ||
                    (userType === 'free' && selectedUserTypes.has('free')) ||
                    (userType === 'first_time' && selectedUserTypes.has('first_time')) ||
                    (userType === 'existing' && selectedUserTypes.has('existing'));

                card.style.display = matchesSearch && matchesStatus && matchesAccess && matchesUserType ? '' : 'none';
            });
        }
    </script>
@endsection