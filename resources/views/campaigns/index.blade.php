@extends('layouts.admin')

@section('title','Campaigns')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
$leadsForJs = \App\Models\Loan::where('step_completed','>=',3)
    ->get()
    ->map(function ($l) {
        return [
            'id' => (string) $l->_id,
            'name' => $l->data['full_name'] ?? 'Unknown',
            'mobile' => $l->data['mobile']
                ?? optional(\App\Models\User::find($l->user_id))->mobile_number
        ];
    })
    ->values();
@endphp

<script>
    window.campaignsFromServer = @json($campaigns);
</script>

<div x-data="campaignManager()" class="space-y-6">

<style>
    [x-cloak] { display: none !important; }
</style>

{{-- ================= HEADER ================= --}}
<div>
    <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
    <p class="text-sm text-gray-500">
        Create and manage bulk SMS & WhatsApp campaigns
    </p>
</div>

{{-- ================= SEARCH + ACTION ================= --}}
<div class="bg-white rounded-xl shadow-sm border p-5">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        {{-- Search --}}
        <div class="relative w-full lg:w-96">
            <input type="text"
                   x-model="search"
                   placeholder="Search campaign by name"
                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
        </div>

        {{-- Action --}}
        <button @click="openModal"
                class="w-full lg:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow">
            + Create Campaign
        </button>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-[900px] w-full border-collapse">
            <thead>
            <tr class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                <th class="px-6 py-3 text-left">Campaign Name</th>
                <th class="px-6 py-3 text-left">Channel</th>
                <th class="px-6 py-3 text-left">Recipients</th>
                <th class="px-6 py-3 text-left">Sent / Schedule</th>
                <th class="px-6 py-3 text-left">Status</th>
            </tr>
            </thead>

            <tbody class="divide-y">
            <template x-for="campaign in filteredCampaigns()" :key="campaign.id">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900"
                        x-text="campaign.name"></td>

                    <td class="px-6 py-4 text-gray-700"
                        x-text="campaign.channel"></td>

                    <td class="px-6 py-4 text-gray-700"
                        x-text="campaign.recipients ?? 0"></td>

                    <td class="px-6 py-4 text-gray-500"
                        x-text="
                            campaign.sent_at
                                ? new Date(campaign.sent_at).toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })
                                : (campaign.scheduled_at
                                    ? new Date(campaign.scheduled_at).toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })
                                    : '-')
                        ">
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full"
                              :class="statusClass(campaign.status)"
                              x-text="campaign.status">
                        </span>
                    </td>
                </tr>
            </template>

            <tr x-show="filteredCampaigns().length === 0">
                <td colspan="5" class="text-center py-8 text-gray-400">
                    No campaigns found
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ================= CREATE CAMPAIGN MODAL ================= --}}
<div x-cloak x-show="showModal"
     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl
                flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
            <h3 class="text-lg font-semibold">Create Campaign</h3>
            <button @click="closeModal"
                    class="text-gray-400 hover:text-gray-600 text-xl">
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">

            <div>
                <label class="text-sm font-medium">Campaign Name</label>
                <input x-model="form.name"
                       class="w-full border px-3 py-2 rounded-lg">
            </div>

            <div>
                <label class="text-sm font-medium">Channel</label>
                <div class="flex flex-wrap gap-3 mt-1">
                    <button type="button"
                            @click="form.channel='SMS'"
                            :class="form.channel==='SMS'
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700'"
                            class="px-6 py-2 rounded-lg">
                        SMS
                    </button>

                    <button type="button"
                            @click="form.channel='WhatsApp'"
                            :class="form.channel==='WhatsApp'
                                ? 'bg-green-600 text-white'
                                : 'bg-gray-200 text-gray-700'"
                            class="px-6 py-2 rounded-lg">
                        WhatsApp
                    </button>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium">Campaign Link</label>
                <input x-model="form.short_link"
                       placeholder="Tracking / short link"
                       class="w-full border px-3 py-2 rounded-lg">
            </div>

            <div>
                <label class="text-sm font-medium">Message</label>
@verbatim
<textarea
    x-model="form.message"
    rows="4"
    class="w-full border px-3 py-2 rounded-lg"
    placeholder="Use {{name}} for personalization">
</textarea>
@endverbatim
                <p class="text-xs text-gray-500 mt-1">
                    WhatsApp uses approved templates (hello_world / approved).
                </p>
            </div>

            <div>
                <label class="text-sm font-medium">Recipients</label>
                <select x-model="form.recipient_type"
                        class="w-full border px-3 py-2 rounded-lg mt-1">
                    <option value="all">All Leads</option>
                    <option value="selected">Selected Leads</option>
                </select>
            </div>

            <div x-show="form.recipient_type === 'selected'" x-cloak>
                <label class="text-sm font-medium block">Select Leads</label>

                <select multiple
                        x-model="form.selected_leads"
                        class="w-full border px-3 py-2 rounded-lg h-40">
                    <template x-for="lead in leads" :key="lead.id">
                        <option :value="lead.id"
                                x-text="lead.name + ' - ' + lead.mobile">
                        </option>
                    </template>
                </select>

                <p class="text-xs text-gray-500 mt-1">
                    Selected leads ko hi message jayega
                </p>
            </div>

            <div>
                <label class="text-sm font-medium">Schedule (optional)</label>
                <input type="datetime-local"
                       x-model="form.scheduled_at"
                       class="w-full border px-3 py-2 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">
                    Leave empty to send immediately
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 px-6 py-4 border-t bg-white flex-shrink-0 rounded-b-xl">

            <button @click="closeModal"
                    class="px-4 py-2 bg-gray-200 rounded-lg">
                Cancel
            </button>

            <button x-show="!form.scheduled_at"
                    @click="saveCampaign"
                    :disabled="loading"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg">
                <span x-show="!loading">Send Now</span>
                <span x-show="loading">Sending...</span>
            </button>

            <button x-show="form.scheduled_at"
                    @click="saveCampaign"
                    :disabled="loading"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg">
                <span x-show="!loading">Schedule Campaign</span>
                <span x-show="loading">Scheduling...</span>
            </button>

        </div>

    </div>
</div>

{{-- ================= ALPINE ================= --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    window.leadsFromServer = @json($leadsForJs);
</script>

<script>
function campaignManager() {
 return {
    search: '',
    showModal: false,
    loading: false,

    campaigns: window.campaignsFromServer ?? [],
    leads: window.leadsFromServer ?? [],

    form: {
        name: '',
        channel: 'SMS',
        short_link: '',
        message: '',
        scheduled_at: null,
        recipient_type: 'all',
        selected_leads: []
    },

    openModal() {
        this.showModal = true;
    },

    closeModal() {
        this.showModal = false;
        this.loading = false;
        this.form = {
            name: '',
            channel: 'SMS',
            short_link: '',
            message: '',
            scheduled_at: null,
            recipient_type: 'all',
            selected_leads: []
        };
    },

    async saveCampaign() {
        this.loading = true;

        const res = await fetch('{{ route("campaigns.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify(this.form)
        });

        const data = await res.json();

        if (data && (data.id || data._id)) {
            this.campaigns.unshift(data);
        }

        this.closeModal();
    },

    filteredCampaigns() {
        return this.campaigns.filter(c =>
            c.name?.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    statusClass(status) {
        return {
            Sent: 'bg-green-100 text-green-800',
            Partial: 'bg-orange-100 text-orange-800',
            Processing: 'bg-yellow-100 text-yellow-800',
            Scheduled: 'bg-blue-100 text-blue-800',
            Draft: 'bg-gray-100 text-gray-800'
        }[status] || 'bg-gray-100 text-gray-800';
    }
 }
}
</script>
@endsection
