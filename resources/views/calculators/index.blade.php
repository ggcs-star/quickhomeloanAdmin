@extends('layouts.admin')

@section('title','Calculators')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-semibold mb-6">Calculators</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        @foreach($calculators as $calc)
            <div class="bg-white shadow-md rounded-xl p-5 border">

                <!-- Title -->
                <h3 class="text-lg font-bold mb-1">
                    {{ $calc->name }}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 text-sm mb-2">
                    {{ $calc->description }}
                </p>

                <!-- Category -->
                <p class="text-xs text-gray-500 mb-3">
                    Category: {{ $calc->category }}
                </p>

                <!-- Access Type Dropdown -->
                <form method="POST" action="{{ route('calculators.updateAccess', $calc->_id) }}">
                    @csrf

                    <label class="text-xs text-gray-500">Access Type</label>

                    <select name="access_type"
                        class="w-full mt-1 mb-3 p-2 border rounded text-sm"
                        onchange="this.form.submit()">

                        <option value="free" {{ $calc->access_type == 'free' ? 'selected' : '' }}>
                            Free
                        </option>

                        <option value="premium" {{ $calc->access_type == 'premium' ? 'selected' : '' }}>
                            Premium
                        </option>

                    </select>
                </form>

                <!-- Status + Toggle -->
                <div class="flex items-center justify-between">

                    <span class="text-sm font-medium 
                        {{ $calc->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $calc->is_active ? 'Active' : 'Inactive' }}
                    </span>

                    <!-- Toggle Switch -->
                    <form method="POST" action="{{ route('calculators.toggle', $calc->_id) }}">
                        @csrf

                        <button type="submit" 
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

</div>

@endsection