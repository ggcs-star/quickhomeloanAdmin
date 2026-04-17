@extends('layouts.admin')
@section('title','Analytics')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-8">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Analytics
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Detailed insights and performance metrics
            </p>
        </div>

        <select
            class="w-full sm:w-auto border border-gray-300 rounded-xl
                   px-4 py-2 text-sm bg-white shadow-sm
                   focus:ring-indigo-500 focus:border-indigo-500">
            <option>Last 30 days</option>
            <option>Last 3 months</option>
            <option>Last 6 months</option>
        </select>
    </div>

    {{-- ================= KPI SUMMARY ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Total Leads</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-indigo-600 mt-1">1,240</h2>
            <p class="text-xs text-green-600 mt-1">▲ 12% growth</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Approved Loans</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-green-600 mt-1">820</h2>
            <p class="text-xs text-green-600 mt-1">▲ 8.4%</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Disbursed Amount</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">₹45.2Cr</h2>
            <p class="text-xs text-green-600 mt-1">▲ 6.1%</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Conversion Rate</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-purple-600 mt-1">68%</h2>
            <p class="text-xs text-red-500 mt-1">▼ 1.2%</p>
        </div>

    </div>

    {{-- ================= CHARTS ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Loan Pipeline --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                Loan Pipeline Overview
            </h3>
            <div class="relative h-[220px] sm:h-[260px]">
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>

        {{-- Revenue Trend --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                Revenue Trend (₹ Cr)
            </h3>
            <div class="relative h-[220px] sm:h-[260px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= LOWER SECTION ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Loan Distribution --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-6 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-6">
                Loan Type Distribution
            </h3>

            <div class="flex flex-col md:flex-row items-center gap-8">

                <div class="w-full md:w-64 flex justify-center">
                    <canvas id="loanTypeChart"></canvas>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3"><span class="w-3 h-3 bg-blue-600 rounded-sm"></span> Home Loan – 65%</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 bg-green-500 rounded-sm"></span> LAP – 20%</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 bg-yellow-400 rounded-sm"></span> Personal – 8%</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 bg-pink-500 rounded-sm"></span> Business – 5%</div>
                    <div class="flex items-center gap-3"><span class="w-3 h-3 bg-purple-500 rounded-sm"></span> Balance Transfer – 2%</div>
                </div>
            </div>
        </div>

        {{-- Top Performers --}}
        <div class="bg-white rounded-2xl shadow p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800 mb-5">
                Top Performers
            </h3>

            <div class="space-y-4 text-sm">
                @foreach([
                    ['Priya Sharma','₹8.5Cr','PS','indigo'],
                    ['Amit Patel','₹7.2Cr','AP','green'],
                    ['Neha Singh','₹6.8Cr','NS','yellow'],
                ] as $p)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-{{ $p[3] }}-100
                                    flex items-center justify-center font-semibold">
                            {{ $p[2] }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $p[0] }}</p>
                            <p class="text-xs text-gray-500">Top closer</p>
                        </div>
                    </div>
                    <span class="font-semibold">{{ $p[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

{{-- ================= CHART JS ================= --}}
<script>
new Chart(pipelineChart, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [
            { label:'Approved', data:[50,60,70,65,80,85], backgroundColor:'#86efac' },
            { label:'Disbursed', data:[40,45,60,55,70,75], backgroundColor:'#fde68a' },
            { label:'Leads', data:[80,90,110,100,120,135], backgroundColor:'#a5b4fc' }
        ]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{ legend:{ position:'bottom' } }
    }
});

new Chart(revenueChart, {
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','May','Jun'],
        datasets:[{
            data:[4.2,4.8,6.5,6.0,7.8,8.3],
            borderColor:'#6366f1',
            backgroundColor:'rgba(99,102,241,.15)',
            tension:.4,
            fill:true,
            pointRadius:4
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{ legend:{ display:false } }
    }
});

new Chart(loanTypeChart, {
    type:'doughnut',
    data:{
        labels:['Home Loan','LAP','Personal','Business','Balance Transfer'],
        datasets:[{
            data:[65,20,8,5,2],
            backgroundColor:['#2563eb','#10b981','#f59e0b','#fb7185','#a855f7']
        }]
    },
    options:{
        responsive:true,
        cutout:'70%',
        plugins:{ legend:{ display:false } }
    }
});
</script>

@endsection
