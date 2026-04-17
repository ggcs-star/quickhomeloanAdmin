@extends('layouts.admin')
@section('title','Documents')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Documents</h1>
        <p class="text-sm text-gray-500">Manage KYC and property documents</p>
    </div>

    {{-- ================= FILTER BAR ================= --}}
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-center">

            {{-- Search --}}
            <div class="relative lg:col-span-2">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                <input type="text"
                       placeholder="Search documents..."
                       class="pl-9 pr-4 py-2 border rounded-lg w-full">
            </div>

            {{-- Status --}}
            <select class="border rounded-lg px-3 py-2 w-full">
                <option>All Statuses</option>
                <option>Verified</option>
                <option>Pending</option>
                <option>Rejected</option>
            </select>

            <div class="lg:col-span-2 flex justify-end">
                <button
                    class="w-full lg:w-auto bg-indigo-600 text-white px-5 py-2 rounded-lg
                           flex items-center justify-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Upload Document
                </button>
            </div>
        </div>
    </div>

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">

        {{-- HEADER --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 text-sm text-gray-500 border-b bg-gray-50">
            <div class="col-span-4">Document</div>
            <div class="col-span-2">Type</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-3">Uploaded</div>
            <div class="col-span-1 text-right">Actions</div>
        </div>

        {{-- ROWS --}}
        @forelse($documents as $doc)
@php
    $loan = $loans[$doc->loan_id] ?? null;
    $leadName = $loan->data['full_name'] ?? 'Unknown';
    $fileName = basename($doc->file_path);
    $status = strtolower($doc->status ?? 'pending');

    $url = str_starts_with($doc->file_path, 'http')
        ? $doc->file_path
        : asset('storage/'.$doc->file_path);

    $color = match($status){
        'verified' => 'green',
        'rejected' => 'red',
        default => 'yellow'
    };
@endphp



            <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b items-center text-sm">

                {{-- Document --}}
                <div class="col-span-4">
                    <p class="font-semibold text-gray-900">{{ $leadName }}</p>
                    <p class="text-gray-500 text-xs break-all">{{ $fileName }}</p>
                </div>

                {{-- Type --}}
                <div class="col-span-2 text-gray-600">
                    Document
                </div>

                {{-- Status --}}
                <div class="col-span-2">
                    <span class="px-3 py-1 rounded-full text-xs
                        bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                {{-- Uploaded --}}
                <div class="col-span-3 text-gray-500">
                    {{ $doc->created_at->format('d M Y') }}
                </div>

                {{-- Actions --}}
                <div class="col-span-1 flex justify-end gap-3 text-gray-600">
                    <button onclick="openDocumentView('{{ $url }}')">
    <i data-lucide="eye" class="w-5 h-5"></i>
</button>


                   <a href="{{ $url }}" download>
                        <i data-lucide="download" class="w-5 h-5"></i>
                    </a>

                    @if(strtolower($doc->status ?? 'pending') === 'pending')
                        <form method="POST"
                              action="{{ route('documents.verify', $doc->_id) }}">
                            @csrf
                            <button
                                class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Verify
                            </button>
                        </form>
                    @endif
                </div>

            </div>

        @empty
            <div class="text-center py-8 text-gray-400">
                No documents uploaded
            </div>
        @endforelse
    </div>

    {{-- ================= MOBILE CARDS ================= --}}
    <div class="md:hidden space-y-4">
        @foreach($documents as $doc)
           @php
    $loan = $loans[$doc->loan_id] ?? null;
    $leadName = $loan->data['full_name'] ?? 'Unknown';
    $fileName = basename($doc->file_path);
    $status = $doc->status ?? 'Pending';

    // ✅ YEH LINE ADD KARO
    $url = str_starts_with($doc->file_path, 'http')
        ? $doc->file_path
        : asset('storage/'.$doc->file_path);

    $color = match(strtolower($status)){
        'verified' => 'green',
        'rejected' => 'red',
        default    => 'yellow'
    };
@endphp


            <div class="bg-white rounded-xl shadow p-4 space-y-3">
                <div>
                    <p class="font-semibold">{{ $loan->data['full_name'] ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-500 break-all">{{ basename($doc->file_path) }}</p>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Document</span>
                    <span class="px-3 py-1 rounded-full text-xs
                          bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ $doc->created_at->format('d M Y') }}</span>
                    <div class="flex gap-4 items-center">
                        <button onclick="openDocumentView('{{ $url }}')">
                            <i data-lucide="eye"></i>
                        </button>

                       <a href="{{ $url }}" download>

                            <i data-lucide="download"></i>
                        </a>
                    </div>
                </div>

                @if(strtolower($doc->status ?? 'pending') === 'pending')
                    <form method="POST" action="{{ route('documents.verify', $doc->_id) }}">
                        @csrf
                        <button class="w-full mt-2 text-xs py-2 bg-green-600 text-white rounded">
                            Verify Document
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

</div>

{{-- ================= DOCUMENT VIEW MODAL ================= --}}
<div id="docViewModal"
     class="fixed inset-0 hidden bg-black/70 flex items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl max-w-3xl w-full p-4 relative">

        <button onclick="closeDocumentView()"
                class="absolute top-3 right-3 text-gray-600 hover:text-black">
            ✕
        </button>

        <div id="docPreview" class="max-h-[80vh] overflow-auto text-center"></div>

    </div>
</div>

<script>
function openDocumentView(url){
    const preview = document.getElementById('docPreview');
    preview.innerHTML = '';

    if(url.match(/\.(jpg|jpeg|png|webp)$/i)){
        preview.innerHTML = `<img src="${url}" class="mx-auto max-h-[75vh] rounded">`;
    } else {
        preview.innerHTML = `<iframe src="${url}" class="w-full h-[75vh]"></iframe>`;
    }

    document.getElementById('docViewModal').classList.remove('hidden');
}

function closeDocumentView(){
    document.getElementById('docViewModal').classList.add('hidden');
}
</script>

@endsection
