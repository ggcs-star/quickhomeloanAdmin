@extends('layouts.admin')
@section('title','Interest Rates')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
            Home Loan Interest Rates
        </h1>
        <p class="text-sm text-gray-500 max-w-3xl">
           As a potential homebuyer, it is essential to compare home loan interest rates offered by different banks to make an informed decision and secure the best deal for your dream home. You should always remember that, Home loan interest rate of all banks can vary based on the factors like loan tenure, loan amount, and the borrower’s credit profile. Here is a brief overview of current housing loan interest rates of different banks in India.
        </p>
    </div>

    {{-- ================= TABS + SORT ================= --}}
    <div class="flex flex-col gap-4 border-b pb-3
                md:flex-row md:items-center md:justify-between">

        {{-- TABS --}}
        <div class="flex gap-4 sm:gap-6 text-sm font-medium
                    overflow-x-auto whitespace-nowrap">

            <a href="{{ route('rates.index',['type'=>'All']) }}"
               class="tab {{ $type=='All'?'active':'' }}">
                All Banks
            </a>

            <a href="{{ route('rates.index',['type'=>'Nationalized']) }}"
               class="tab {{ $type=='Nationalized'?'active':'' }}">
                Nationalized Bank
            </a>

            <a href="{{ route('rates.index',['type'=>'Private']) }}"
               class="tab {{ $type=='Private'?'active':'' }}">
                Private Bank
            </a>

            <a href="{{ route('rates.index',['type'=>'NBFC']) }}"
               class="tab {{ $type=='NBFC'?'active':'' }}">
                NBFC Bank
            </a>
        </div>

        {{-- SORT --}}
        <div class="w-full sm:w-auto">
            <select class="border rounded-lg px-3 py-2 text-sm text-gray-700
                           w-full sm:w-auto">
                <option>Sort by: Low to High</option>
                <option>Sort by: High to Low</option>
            </select>
        </div>
    </div>

    {{-- ================= SECTION TITLE ================= --}}
    <h2 class="text-base sm:text-lg font-semibold text-gray-900">
        Top {{ $type }} Bank Home Loan Interest Rates - October 2025
    </h2>

    {{-- ================= CARDS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($lenders as $lender)
        @php
            $p = $lender->products[0] ?? null;
        @endphp

        <div class="bg-white rounded-xl shadow border
                    hover:shadow-lg transition
                    flex flex-col">

            {{-- CARD BODY --}}
            <div class="p-4 sm:p-5 space-y-4 flex-1">

                {{-- BANK LOGO + NAME --}}
                <div class="flex items-center gap-3 flex-wrap">

                    @php
                        $logo = $lender->logo;

                        if ($logo && str_starts_with($logo, 'http')) {
                            $logoUrl = $logo;
                        } elseif ($logo) {
                            $logoUrl = asset('storage/'.$logo);
                        } else {
                            $logoUrl = asset('images/bank-placeholder.png');
                        }
                    @endphp

                    <img src="{{ $logoUrl }}"
                         alt="{{ $lender->name }}"
                         class="h-10 w-auto object-contain flex-shrink-0">

                    <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">
                        {{ $lender->name }}
                    </h3>
                </div>

                {{-- DETAILS --}}
                <div class="grid grid-cols-2 gap-3 sm:gap-4 text-sm">

                    <div>
                        <p class="text-gray-500">Interest Rate</p>
                        <p class="text-indigo-600 font-bold text-lg">
                            {{ $p['rate'] ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">EMI Per Lakh</p>
                        <p class="font-semibold text-gray-900">
                            {{ $p['emi'] ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Loan Amount</p>
                        <p class="font-semibold text-gray-900">
                            {{ $p['loan'] ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Tenure</p>
                        <p class="font-semibold text-gray-900">
                            {{ $p['tenure'] ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- CARD FOOTER --}}
            <div class="border-t py-3 text-center">
                <button class="text-indigo-600 font-medium hover:underline text-sm">
                    Enquire
                </button>
            </div>

        </div>

        @empty
            <p class="text-gray-500 col-span-full text-center">
                No lenders found for this category.
            </p>
        @endforelse

    </div>

</div>

{{-- ================= STYLES ================= --}}
<style>
.tab{
    padding-bottom:10px;
    color:#6b7280;
}
.tab.active{
    color:#4f46e5;
    border-bottom:2px solid #4f46e5;
}
</style>

@endsection
