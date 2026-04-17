@extends('layouts.admin')
@section('title','Projects')

@section('content')

<div class="space-y-6">

    {{-- ================= PROJECT LIST ================= --}}
    <div id="projectsList" class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Projects</h1>
                <p class="text-sm text-gray-500">Manage builder projects and AFP status</p>
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Project
            </button>
        </div>

        {{-- SEARCH --}}
        <div class="bg-white rounded-xl shadow p-4">
            <div class="relative w-full sm:max-w-md">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                <input type="text"
                       placeholder="Search by project, builder, or location..."
                       class="pl-9 pr-4 py-2 border rounded-lg w-full">
            </div>
        </div>

        {{-- CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 sm:p-5">
                <h3 class="font-semibold text-base sm:text-lg">Godrej Panorama</h3>

                <div class="text-sm text-gray-500 mt-2 space-y-1">
                    <div class="flex gap-2 items-center">
                        <i data-lucide="building" class="w-4 h-4"></i> Godrej Properties
                    </div>
                    <div class="flex gap-2 items-center">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Pune
                    </div>
                </div>

                <div class="flex justify-between items-center mt-4 text-sm">
                    <div class="flex gap-4">
                        <span class="flex items-center gap-1 text-green-600">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> 1
                        </span>
                        <span class="flex items-center gap-1 text-yellow-500">
                            <i data-lucide="clock" class="w-4 h-4"></i> 0
                        </span>
                    </div>

                    <button onclick="openDetails()"
                            class="text-indigo-600 hover:underline">
                        View Details
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= PROJECT DETAILS ================= --}}
    <div id="projectDetails" class="hidden space-y-6">

        <button onclick="backToList()"
                class="text-indigo-600 text-sm flex items-center gap-1">
            ← Back to Projects
        </button>

        {{-- PROJECT INFO --}}
        <div class="bg-white rounded-xl shadow p-4 sm:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold">Godrej Panorama</h1>

                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    <div class="flex gap-2 items-center">
                        <i data-lucide="building" class="w-4 h-4 text-indigo-600"></i>
                        Godrej Properties
                    </div>
                    <div class="flex gap-2 items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                        Pune
                    </div>
                </div>
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 w-full md:w-auto justify-center">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>

        {{-- AFP STATUS --}}
        <div class="bg-white rounded-xl shadow p-4 sm:p-6 space-y-4">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <h2 class="font-semibold text-base sm:text-lg">AFP Status</h2>
                <button class="text-indigo-600 text-sm flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Bank Status
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-center">
                <select class="border rounded-lg px-3 py-2 w-full">
                    <option>HDFC Bank</option>
                    <option>ICICI Bank</option>
                    <option>SBI</option>
                    <option>Axis Bank</option>
                    <option>Kotak Mahindra Bank</option>
                    <option>PNB Housing</option>
                    <option>LIC Housing</option>
                </select>

                <select class="border rounded-lg px-3 py-2 w-full">
                    <option selected>Approved</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>

                <input type="text"
                       class="border rounded-lg px-3 py-2 w-full"
                       value="Pre-approved for all configurations">

                <button class="text-red-500 flex justify-center">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

    </div>

</div>

{{-- ================= JS ================= --}}
<script>
function openDetails() {
    document.getElementById('projectsList').classList.add('hidden');
    document.getElementById('projectDetails').classList.remove('hidden');
}

function backToList() {
    document.getElementById('projectDetails').classList.add('hidden');
    document.getElementById('projectsList').classList.remove('hidden');
}
</script>

@endsection
