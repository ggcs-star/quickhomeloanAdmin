@extends('layouts.admin')
@section('title','Partners (DSA)')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                Partners (DSA)
            </h1>
            <p class="text-sm text-gray-600">
                Manage and track partner performance
            </p>
        </div>

        <button onclick="openPartnerModal()"
            class="bg-indigo-600 hover:bg-indigo-700 transition
                   text-white px-4 py-2 rounded-lg
                   flex items-center gap-2 text-sm font-medium
                   w-full sm:w-auto justify-center">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Partner
        </button>
    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="relative w-full sm:max-w-lg">
            <i data-lucide="search"
               class="w-4 h-4 absolute left-3 top-3.5 text-gray-400"></i>
            <input
                type="text"
                placeholder="Search partners by name or email..."
                class="pl-10 pr-4 py-2.5 w-full
                       border border-gray-300
                       rounded-lg text-sm
                       focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    {{-- ================= PARTNER CARDS ================= --}}
    @if($partners->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($partners as $row)
        @php
            $partner = $row['partner'];
            $initials = strtoupper(substr($partner->name,0,2));
            $colors = ['indigo','green','yellow','purple','pink'];
            $color = $colors[$loop->index % count($colors)];
        @endphp

        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col">

            <div class="p-5 space-y-5 flex-1">

                {{-- TOP --}}
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full
                                bg-{{ $color }}-100 text-{{ $color }}-700
                                flex items-center justify-center
                                font-bold text-sm">
                        {{ $initials }}
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">
                            {{ $partner->name }}
                        </h3>
                        <p class="text-sm text-gray-600 truncate">
                            {{ $partner->email }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $partner->mobile }}
                        </p>
                    </div>
                </div>

                {{-- STATS --}}
                <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <p class="text-gray-500">Referrals</p>
                        <p class="font-semibold">{{ $row['referrals'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Conversion</p>
                        <p class="font-semibold">{{ $row['conversion'] }}%</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Disbursed</p>
                        <p class="font-semibold">
                            ₹{{ number_format($row['disbursedAmount']) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Commission</p>
                        <p class="font-semibold">
                            ₹{{ number_format($row['commission']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="border-t py-3 flex justify-center gap-6 text-sm">

                <button
                    onclick="openEditModal(
                        '{{ $partner->_id }}',
                        '{{ $partner->name }}',
                        '{{ $partner->email }}',
                        '{{ $partner->mobile }}',
                        '{{ $partner->commission_rate ?? 1 }}',
                        '{{ $partner->status }}'
                    )"
                    class="text-indigo-600 hover:underline">
                    Edit
                </button>

                <form method="POST"
                      action="{{ route('partners.destroy', $partner->_id) }}"
                      onsubmit="return confirm('Delete this partner?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline">
                        Delete
                    </button>
                </form>

            </div>
        </div>
        @endforeach
    </div>

    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-xl p-10 text-center text-gray-500">
            No DSA partners found. Add your first partner 🚀
        </div>
    @endif

</div>

{{-- ================= ADD PARTNER MODAL ================= --}}
<div id="partnerModal"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <form method="POST" action="{{ route('partners.store') }}"
          class="bg-white rounded-xl p-6 w-full max-w-sm space-y-4">
        @csrf
        <h3 class="text-lg font-semibold">Add DSA Partner</h3>

        <input name="name" placeholder="Name"
               class="w-full border px-3 py-2 rounded" required>
        <input name="email" placeholder="Email"
               class="w-full border px-3 py-2 rounded" required>
        <input name="mobile" placeholder="Mobile"
               class="w-full border px-3 py-2 rounded" required>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="closePartnerModal()">Cancel</button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Save
            </button>
        </div>
    </form>
</div>

{{-- ================= EDIT PARTNER MODAL ================= --}}
<div id="editPartnerModal"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <form method="POST" id="editPartnerForm"
          class="bg-white rounded-xl p-6 w-full max-w-sm space-y-4">
        @csrf

        <h3 class="text-lg font-semibold">Edit DSA Partner</h3>

        <input name="name" id="edit_name"
               class="w-full border px-3 py-2 rounded" required>

        <input name="email" id="edit_email"
               class="w-full border px-3 py-2 rounded" required>

        <input name="mobile" id="edit_mobile"
               class="w-full border px-3 py-2 rounded" required>

        <input name="commission_rate" id="edit_commission"
               type="number" step="0.1"
               class="w-full border px-3 py-2 rounded">

        <select name="status" id="edit_status"
                class="w-full border px-3 py-2 rounded">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeEditModal()">Cancel</button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>
    </form>
</div>

{{-- ================= SCRIPTS ================= --}}
<script>
function openPartnerModal() {
    document.getElementById('partnerModal').classList.remove('hidden');
}
function closePartnerModal() {
    document.getElementById('partnerModal').classList.add('hidden');
}

function openEditModal(id, name, email, mobile, commission, status) {
    const form = document.getElementById('editPartnerForm');

    form.action = `/partners/${id}/update`;

    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_mobile').value = mobile;
    document.getElementById('edit_commission').value = commission;
    document.getElementById('edit_status').value = status;

    document.getElementById('editPartnerModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editPartnerModal').classList.add('hidden');
}
</script>

@endsection
