@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    canvas { max-width: 100% !important; }
</style>

<div class="w-full px-4 sm:px-6 py-5 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Real-time insights and analytics</p>
        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-100 transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Logout
            </button>
        </form>
    </div>

    {{-- ================= TOP STATS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Leads</p>
                <h2 class="text-xl sm:text-2xl font-bold">248</h2>
                <p class="text-green-600 text-sm">+12.5%</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                <i data-lucide="indian-rupee" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Disbursal</p>
                <h2 class="text-xl sm:text-2xl font-bold">₹45.2Cr</h2>
                <p class="text-green-600 text-sm">+8.3%</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Conversion Rate</p>
                <h2 class="text-xl sm:text-2xl font-bold">68%</h2>
                <p class="text-green-600 text-sm">+3.2%</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-red-100 text-red-600 p-3 rounded-lg">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Documents Pending</p>
                <h2 class="text-xl sm:text-2xl font-bold">42</h2>
                <p class="text-red-600 text-sm">-5.1%</p>
            </div>
        </div>

    </div>

    {{-- ================= PROJECT OVERVIEW ================= --}}
    <div>
        <h3 class="font-semibold text-gray-800 mb-3 text-base sm:text-lg">Project Overview</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['building','Total Projects','4'],
                ['check-circle','AFP Approved','2'],
                ['clock','Pending Approvals','1']
            ] as $p)
                <div class="bg-white rounded-xl shadow p-4 sm:p-5 flex items-center gap-4">
                    <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg">
                        <i data-lucide="{{ $p[0] }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ $p[1] }}</p>
                        <h2 class="text-xl sm:text-2xl font-bold">{{ $p[2] }}</h2>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= CHARTS ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow p-5 sm:p-6">
            <h3 class="font-semibold mb-4">Loan Pipeline</h3>
            <div class="relative h-[220px] sm:h-[260px]">
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 sm:p-6">
            <h3 class="font-semibold mb-4">Revenue Trend (in Cr)</h3>
            <div class="relative h-[220px] sm:h-[260px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= DISTRIBUTION + TOP ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-5 sm:p-6 lg:col-span-2">
            <h3 class="font-semibold mb-4">Loan Type Distribution</h3>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="w-52 sm:w-64">
                    <canvas id="loanTypeChart"></canvas>
                </div>

                <div class="space-y-3 text-sm sm:text-base">
                    <div class="flex items-center gap-2 text-blue-600"><span class="w-3 h-3 bg-blue-500 rounded-sm"></span> Home Loan (65%)</div>
                    <div class="flex items-center gap-2 text-green-600"><span class="w-3 h-3 bg-green-500 rounded-sm"></span> LAP (20%)</div>
                    <div class="flex items-center gap-2 text-yellow-500"><span class="w-3 h-3 bg-yellow-400 rounded-sm"></span> Personal (8%)</div>
                    <div class="flex items-center gap-2 text-orange-500"><span class="w-3 h-3 bg-orange-400 rounded-sm"></span> Business (5%)</div>
                    <div class="flex items-center gap-2 text-purple-600"><span class="w-3 h-3 bg-purple-500 rounded-sm"></span> Balance Transfer (2%)</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 sm:p-6">
            <h3 class="font-semibold mb-4">Top Performers</h3>
            <div class="space-y-3 text-sm sm:text-base">
                <div class="flex justify-between"><span>Priya Sharma</span><span class="font-semibold">₹8.5Cr</span></div>
                <div class="flex justify-between"><span>Amit Patel</span><span class="font-semibold">₹7.2Cr</span></div>
                <div class="flex justify-between"><span>Neha Singh</span><span class="font-semibold">₹6.8Cr</span></div>
                <div class="flex justify-between"><span>Rahul Verma</span><span class="font-semibold">₹5.9Cr</span></div>
                <div class="flex justify-between"><span>Kavita Rao</span><span class="font-semibold">₹5.4Cr</span></div>
            </div>
        </div>
    </div>

</div>

{{-- ================= CHART JS ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    new Chart(pipelineChart, {
        type:'bar',
        data:{
            labels:['Jan','Feb','Mar','Apr','May','Jun'],
            datasets:[
                { label:'Approved', data:[45,60,70,65,78,82], backgroundColor:'#86efac' },
                { label:'Disbursed', data:[38,42,60,55,70,75], backgroundColor:'#fdba74' },
                { label:'Leads', data:[78,88,110,98,120,130], backgroundColor:'#818cf8' }
            ]
        },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
    });

    new Chart(revenueChart, {
        type:'line',
        data:{ labels:['Jan','Feb','Mar','Apr','May','Jun'],
            datasets:[{ data:[4.2,4.8,6.5,6.0,7.8,8.2], borderColor:'#6366f1', tension:.4 }]
        },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } } }
    });

    new Chart(loanTypeChart, {
        type:'doughnut',
        data:{
            labels:['Home Loan','LAP','Personal','Business','Balance Transfer'],
            datasets:[{ data:[65,20,8,5,2], backgroundColor:['#3b82f6','#10b981','#facc15','#fb923c','#a855f7'], borderWidth:0 }]
        },
        options:{ cutout:'70%', plugins:{ legend:{ display:false } } }
    });

});
</script>

@endsection
