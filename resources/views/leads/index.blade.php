@extends('layouts.admin')
@section('title', 'Leads')

@section('content')
    @php
        $view = $view ?? 'board';
    @endphp
    @php
        $stageMap = [
            'Contacted' => 1,
            'Document Collected' => 2,
            'Verification' => 3,
            'Loan Offer Shared' => 4,
            'Negotiation / Follow-up' => 5,
            'Approved / Sanctioned' => 6,
            'Disbursed / Closed' => 7,
            'Lost / Rejected' => 9,
        ];
    @endphp

    <div class="h-full flex flex-col bg-gray-50">

        <!-- ================= HEADER ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lead Pipeline</h1>
                <p class="text-sm text-gray-500 mt-1">Track, review and move leads across stages</p>
            </div>

            <button onclick="openAddLeadModal()"
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow">
                Add Lead
            </button>
        </div>

        <!-- ================= FILTER BAR ================= -->
        <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="hidden" name="view" value="{{ $view }}">

                <div class="relative w-full sm:w-56">
                    <input name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search leads..."
                        autocomplete="off" class="pl-9 pr-4 py-2 border rounded-lg text-sm w-full">
                    <span class="absolute left-3 top-2.5 text-gray-400"></span>

                    <!-- Search Results Dropdown -->
                    <div id="searchResults"
                        class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto">
                    </div>
                </div>

                <select name="source" class="border rounded-lg px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="">All Sources</option>
                    <option value="Website">Website</option>
                    <option value="Digital Ad">Digital Ad</option>
                    <option value="Partner">Partner</option>
                    <option value="Manual">Manual</option>
                </select>

                <select name="stage" class="border rounded-lg px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="">All Stages</option>
                    @foreach(array_keys($leadsByStage->toArray()) as $stage)
                        <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>
                            {{ $stage }}
                        </option>
                    @endforeach
                </select>

                <select name="amount" class="border rounded-lg px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="">All Loan Amounts</option>
                    <option value="<25">&lt; ₹25 Lakh</option>
                    <option value="25-50">₹25–50 Lakh</option>
                    <option value="50-100">₹50 Lakh – ₹1 Cr</option>
                    <option value=">100">&gt; ₹1 Cr</option>
                </select>

                <select name="cibil" class="border rounded-lg px-3 py-2 text-sm w-full sm:w-auto">
                    <option value="">All CIBIL Scores</option>
                    <option value="750+" {{ request('cibil') == '750+' ? 'selected' : '' }}>750+</option>
                    <option value="700-749" {{ request('cibil') == '700-749' ? 'selected' : '' }}>700 – 749</option>
                    <option value="650-699" {{ request('cibil') == '650-699' ? 'selected' : '' }}>650 – 699</option>
                    <option value="<650" {{ request('cibil') == '<650' ? 'selected' : '' }}>&lt; 650</option>
                </select>

                <div class="flex bg-gray-100 rounded-lg p-1 ml-auto w-full sm:w-auto justify-center sm:justify-start">
                    <a href="{{ route('leads', ['view' => 'board']) }}"
                        class="px-4 py-2 text-sm rounded-md {{ $view == 'board' ? 'bg-white shadow font-medium' : 'text-gray-600' }}">
                        Board
                    </a>
                    <a href="{{ route('leads', ['view' => 'table']) }}"
                        class="px-4 py-2 text-sm rounded-md {{ $view == 'table' ? 'bg-white shadow font-medium' : 'text-gray-600' }}">
                        Table
                    </a>
                </div>
            </form>
        </div>

        <!-- ================= BOARD VIEW ================= -->
        @if($view === 'board')
            <div class="flex-1 overflow-x-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 min-w-[1200px]">

                    @foreach($leadsByStage as $stageName => $items)
                        <div class="bg-white rounded-xl border flex flex-col max-h-[78vh]">

                            <div class="px-4 py-3 border-b bg-indigo-50 flex justify-between">
                                <h3 class="text-sm font-semibold text-indigo-700">{{ $stageName }}</h3>
                                <span class="text-xs bg-indigo-600 text-white px-2 py-0.5 rounded-full">
                                    {{ count($items) }}
                                </span>
                            </div>

                            <div class="p-3 space-y-3 overflow-y-auto">
                                @forelse($items as $lead)
                                    <div class="lead-card border rounded-lg p-3 cursor-pointer hover:shadow" data-lead='@json($lead)'
                                        onclick="openLeadView(event, this)">

                                        <div class="font-semibold text-sm">{{ $lead->data['full_name'] ?? '-' }}</div>
                                        <div class="text-xs text-gray-600">{{ $lead->email ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->mobile ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $lead->data['city'] ?? '-' }}</div>

                                        <div class="font-bold mt-1">
                                            ₹ {{ number_format($lead->data['loan_amount'] ?? 0) }}
                                        </div>

                                        <div class="flex flex-wrap justify-end gap-2 mt-2">
                                            @foreach($nextActions[$stageName] ?? [] as $nextStage)

                                                @if($nextStage === 'Document Collected')
                                                    <button onclick="event.stopPropagation(); openDocumentModal('{{ $lead->_id }}')"
                                                        class="text-xs px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                                        {{ $nextStage }}
                                                    </button>

                                                @elseif($nextStage === 'Verification')
                                                    {{-- intentionally hidden --}}

                                                @else
                                                    <button onclick="moveLeadStage(event, '{{ $lead->_id }}', {{ $stageMap[$nextStage] }})"
                                                        class="text-xs px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                                        {{ $nextStage }}
                                                    </button>
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-sm text-gray-400 py-6">No leads</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif

    </div>

    <!-- ================= VIEW MODAL ================= -->
    <div id="viewLeadModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white max-w-xl w-full rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Lead Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <p><b>Name</b><br><span id="v_name"></span></p>
                <p><b>Email</b><br><span id="v_email"></span></p>
                <p><b>Phone</b><br><span id="v_contact"></span></p>
                <p><b>City</b><br><span id="v_city"></span></p>
                <p><b>PAN</b><br><span id="v_pan"></span></p>
                <p><b>DOB</b><br><span id="v_dob"></span></p>
                <p><b>Employment</b><br><span id="v_emp"></span></p>
                <p><b>Income</b><br>₹ <span id="v_income"></span></p>
                <p><b>EMI</b><br>₹ <span id="v_emi"></span></p>
                <p class="sm:col-span-2 font-semibold text-lg">
                    Loan Amount: ₹ <span id="v_amount"></span>
                </p>
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <button onclick="openEditFromView()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Edit
                </button>
                <button onclick="deleteFromView()" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Delete
                </button>
                <button onclick="closeLeadView()" class="px-4 py-2 border rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- ================= ADD / EDIT MODAL ================= -->
    <div id="leadFormModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white max-w-3xl w-full rounded-xl p-6">
            <h3 id="leadFormTitle" class="text-lg font-semibold mb-4">Add Lead</h3>

            <form id="leadForm">
                @csrf
                <input type="hidden" id="lead_id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input id="f_name" placeholder="Full Name" class="border px-3 py-2 rounded">
                    <input id="f_city" placeholder="City" class="border px-3 py-2 rounded">
                    <input id="f_email" placeholder="Email" class="border px-3 py-2 rounded">
                    <input id="f_mobile" placeholder="Mobile" class="border px-3 py-2 rounded">
                    <input id="f_amount" placeholder="Loan Amount" class="border px-3 py-2 rounded">
                    <select id="f_employment" class="border px-3 py-2 rounded">
                        <option value="">Employment</option>
                        <option>Salaried</option>
                        <option>Business</option>
                    </select>
                    <input id="f_pan" placeholder="PAN" class="border px-3 py-2 rounded">
                    <input id="f_dob" type="date" class="border px-3 py-2 rounded">
                    <input id="f_income" placeholder="Income" class="border px-3 py-2 rounded">
                    <input id="f_emi" placeholder="EMI" class="border px-3 py-2 rounded">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeLeadForm()" class="px-4 py-2 border rounded">
                        Cancel
                    </button>
                    <button type="button" onclick="saveLead()" class="px-6 py-2 bg-indigo-600 text-white rounded">
                        Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔒 ALL JS BELOW UNCHANGED --}}

    <script>
        let selectedLead = null;
        /* =====================================================
           SIMPLE TOAST MESSAGE
        ===================================================== */
        function showMsg(text, type = 'success') {
            const div = document.createElement('div');
            div.innerText = text;
            div.className =
                `fixed top-5 right-5 z-50 px-4 py-2 rounded shadow text-white
             ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2500);
        }

        /* =====================================================
           ADD LEAD
        ===================================================== */
        window.openAddLeadModal = function () {
            document.getElementById('leadFormTitle').innerText = 'Add Lead';
            document.getElementById('lead_id').value = '';
            document.getElementById('leadForm').reset();
            document.getElementById('leadFormModal').classList.remove('hidden');
        };


        window.openEditLead = function (lead) {

            // 🔴 VERY IMPORTANT: close view modal first
            document.getElementById('viewLeadModal').classList.add('hidden');

            // title
            document.getElementById('leadFormTitle').innerText = 'Edit Lead';

            // hidden id
            document.getElementById('lead_id').value = lead.lead_id;

            document.getElementById('f_name').value = lead.data?.full_name || '';
            document.getElementById('f_city').value = lead.data?.city || '';
            document.getElementById('f_email').value = lead.email || '';
            document.getElementById('f_mobile').value = lead.mobile || '';
            document.getElementById('f_amount').value = lead.data?.loan_amount || '';
            document.getElementById('f_employment').value = lead.data?.employment_type || '';
            document.getElementById('f_pan').value = lead.data?.pan || '';
            document.getElementById('f_dob').value = lead.data?.dob || '';
            document.getElementById('f_income').value = lead.data?.income || '';
            document.getElementById('f_emi').value = lead.data?.existing_emi || '';

            // open edit modal
            document.getElementById('leadFormModal').classList.remove('hidden');
        };



        window.closeLeadForm = function () {
            document.getElementById('leadFormModal').classList.add('hidden');
        };


        window.closeLeadView = function () {
            document.getElementById('viewLeadModal').classList.add('hidden');
        };
        window.saveLead = function () {

            const id = document.getElementById('lead_id').value;

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');

            fd.append('full_name', f_name.value);
            fd.append('city', f_city.value);
            fd.append('email', f_email.value);
            fd.append('mobile', f_mobile.value);
            fd.append('loan_amount', f_amount.value);
            fd.append('employment_type', f_employment.value);
            fd.append('pan', f_pan.value);
            fd.append('dob', f_dob.value);
            fd.append('income', f_income.value);
            fd.append('existing_emi', f_emi.value);

            fetch(id ? `/leads/${id}/update` : `/leads`, {
                method: 'POST',
                body: fd
            })
                .then(res => {
                    if (!res.ok) throw new Error();
                    showMsg(id ? 'Lead updated successfully' : 'Lead added successfully');
                    setTimeout(() => location.reload(), 800);
                })
                .catch(() => showMsg('Save / Update failed', 'error'));
        };


        /* =====================================================
           DELETE
        ===================================================== */
        window.deleteLead = function (id) {
            if (!confirm('Delete this lead?')) return;

            fetch(`/leads/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
                .then(() => {
                    showMsg('Lead deleted');
                    setTimeout(() => location.reload(), 800);
                })
                .catch(() => showMsg('Delete failed', 'error'));
        };
    </script>
    <script>
        window.openLeadView = function (e, el) {

            selectedLead = JSON.parse(el.dataset.lead);

            document.getElementById('v_name').innerText = selectedLead.data?.full_name || '-';
            document.getElementById('v_email').innerText = selectedLead.email || '-';
            document.getElementById('v_contact').innerText = selectedLead.mobile || '-';
            document.getElementById('v_city').innerText = selectedLead.data?.city || '-';
            document.getElementById('v_pan').innerText = selectedLead.data?.pan || '-';
            document.getElementById('v_dob').innerText = selectedLead.data?.dob || '-';
            document.getElementById('v_emp').innerText = selectedLead.data?.employment_type || '-';
            document.getElementById('v_income').innerText = selectedLead.data?.income || '-';
            document.getElementById('v_emi').innerText = selectedLead.data?.existing_emi || '-';
            document.getElementById('v_amount').innerText =
                Number(selectedLead.data?.loan_amount || 0).toLocaleString('en-IN');

            document.getElementById('viewLeadModal').classList.remove('hidden');
        };

        window.openEditFromView = function () {

            if (!selectedLead) return;

            // close view modal
            document.getElementById('viewLeadModal').classList.add('hidden');

            // open edit form with same data
            openEditLead(selectedLead);
        };
        window.deleteFromView = function () {

            if (!selectedLead) return;

            if (!confirm('Delete this lead?')) return;

            // close view modal
            document.getElementById('viewLeadModal').classList.add('hidden');

            // delete using existing logic
            deleteLead(selectedLead.lead_id);
        };

    </script>


    <script>
        function moveLeadStage(e, leadId, stage) {
            e.stopPropagation(); // card click / modal open stop

            fetch(`/leads/${leadId}/stage`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ stage })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        showMsg('Moved to ' + stage);
                        setTimeout(() => location.reload(), 400);
                    }
                })
                .catch(() => showMsg('Stage update failed', 'error'));
        }
    </script>
    <div id="documentModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50">

        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data"
            class="bg-white w-full max-w-sm rounded-xl p-6 space-y-4">

            @csrf

            <!-- ✅ MUST be loan_id -->
            <input type="hidden" name="loan_id" id="doc_loan_id">

            <h3 class="text-lg font-semibold">Upload Document</h3>

            <input type="file" name="file" class="w-full border rounded px-3 py-2" required>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDocumentModal()" class="px-4 py-2 border rounded">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Upload
                </button>
            </div>
        </form>
    </div>

    <script>
        function openDocumentModal(loanId) {
            document.getElementById('doc_loan_id').value = loanId;
            document.getElementById('documentModal').classList.remove('hidden');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
        }
    </script>


    <script>
        // =====================================================
        // 🔍 LIVE SEARCH WITH DEBOUNCE
        // =====================================================

        let searchTimeout;
        let searchResults = [];

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const searchResultsDiv = document.getElementById('searchResults');

            // 🎯 Debounced Search on Input
            searchInput.addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();

                if (query.length === 0) {
                    searchResultsDiv.classList.add('hidden');
                    return;
                }

                // Minimum 2 characters to search
                if (query.length < 2) {
                    searchResultsDiv.classList.add('hidden');
                    return;
                }

                // Debounce: wait 400ms after user stops typing
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 400);
            });

            // 🎯 Show results on focus if there's text
            searchInput.addEventListener('focus', function (e) {
                const query = e.target.value.trim();
                if (query.length >= 2 && searchResults.length > 0) {
                    displaySearchResults(searchResults);
                    searchResultsDiv.classList.remove('hidden');
                }
            });

            // 🎯 Hide results when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !searchResultsDiv.contains(e.target)) {
                    searchResultsDiv.classList.add('hidden');
                }
            });

            // 🎯 Keyboard navigation
            let selectedIndex = -1;

            searchInput.addEventListener('keydown', function (e) {
                const items = searchResultsDiv.querySelectorAll('.search-item');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    highlightItem(items, selectedIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    highlightItem(items, selectedIndex);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && items[selectedIndex]) {
                        items[selectedIndex].click();
                    }
                } else if (e.key === 'Escape') {
                    searchResultsDiv.classList.add('hidden');
                    selectedIndex = -1;
                }
            });
        });

        // 🔍 Perform Search API Call
        function performSearch(query) {
            const searchResultsDiv = document.getElementById('searchResults');

            // Show loading state
            searchResultsDiv.innerHTML = `
            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                <span class="inline-block animate-spin mr-2"></span>
                Searching...
            </div>
        `;
            searchResultsDiv.classList.remove('hidden');

            fetch(`/leads/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    searchResults = data;
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResultsDiv.innerHTML = `
                <div class="px-4 py-3 text-sm text-red-500 text-center">
                    Search failed. Please try again.
                </div>
            `;
                });
        }

        // 📋 Display Search Results
        function displaySearchResults(results) {
            const searchResultsDiv = document.getElementById('searchResults');

            if (!results || results.length === 0) {
                searchResultsDiv.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                    <span class="mr-2"></span> No leads found
                </div>
            `;
                return;
            }

            let html = '';

            results.forEach((lead, index) => {
                html += `
                <div class="search-item px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b last:border-b-0 transition"
                     onclick="selectSearchResult('${lead.id}')"
                     data-index="${index}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-900">
                                ${highlightMatch(lead.name, document.getElementById('searchInput').value)}
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                ${lead.mobile ? `
                                    <span class="mr-3"> ${lead.mobile}</span>
                                ` : ''}
                                ${lead.email ? `
                                    <span> ${lead.email}</span>
                                ` : ''}
                            </div>
                        </div>
                        <div class="text-xs text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            `;
            });

            // Add "View All" button if more than 5 results
            if (results.length >= 5) {
                html += `
                <div class="px-4 py-2 bg-gray-50 text-center border-t">
                    <button onclick="viewAllResults()" 
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                        View all ${results.length} results →
                    </button>
                </div>
            `;
            }

            searchResultsDiv.innerHTML = html;
            selectedIndex = -1;
        }

        // 🎨 Highlight matching text
        function highlightMatch(text, query) {
            if (!text || !query) return text || '';

            const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200 px-0.5 rounded">$1</mark>');
        }

        // 🔒 Escape special characters for regex
        function escapeRegex(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // 👆 Highlight selected item (keyboard navigation)
        function highlightItem(items, index) {
            items.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('bg-indigo-50', 'border-l-4', 'border-indigo-600');
                } else {
                    item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-600');
                }
            });
        }

        // 👆 Select search result
        function selectSearchResult(leadId) {
            // Close dropdown
            document.getElementById('searchResults').classList.add('hidden');

            // Find the lead card in pipeline and scroll to it
            const leadCards = document.querySelectorAll('.lead-card');
            let targetCard = null;

            leadCards.forEach(card => {
                try {
                    const leadData = JSON.parse(card.dataset.lead);
                    if (leadData._id === leadId || leadData.lead_id === leadId) {
                        targetCard = card;
                    }
                } catch (e) {
                    console.error('Error parsing lead data:', e);
                }
            });

            if (targetCard) {
                // Highlight and scroll to the card
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetCard.classList.add('ring-4', 'ring-indigo-500', 'ring-opacity-50');

                // Remove highlight after 3 seconds
                setTimeout(() => {
                    targetCard.classList.remove('ring-4', 'ring-indigo-500', 'ring-opacity-50');
                }, 3000);

                // Open lead view modal
                openLeadView(null, targetCard);
            } else {
                showMsg('Lead not found in current view', 'error');
            }
        }

        // 👁️ View all results (trigger full page search)
        function viewAllResults() {
            const searchInput = document.getElementById('searchInput');
            const query = searchInput.value.trim();

            if (query.length >= 2) {
                // Submit the form to reload page with search results
                searchInput.closest('form').submit();
            }
        }

        // 📊 Show search stats in filter bar
        function updateSearchStats(count, query) {
            let statsDiv = document.getElementById('searchStats');

            if (!statsDiv) {
                statsDiv = document.createElement('div');
                statsDiv.id = 'searchStats';
                statsDiv.className = 'text-xs text-gray-500 mt-2';
                document.getElementById('searchInput').parentElement.appendChild(statsDiv);
            }

            statsDiv.innerHTML = count > 0
                ? `Found <strong>${count}</strong> leads for "${query}"`
                : '';
        }
    </script>
@endsection