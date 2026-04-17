@extends('layouts.admin')
@section('title','Lenders / Banks')

@section('content')

<div class="space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Lenders / Banks</h1>
            <p class="text-sm text-gray-500">Compare rates and manage lender partnerships</p>
        </div>

        <button onclick="openAddLender()"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Lender
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="flex flex-wrap gap-2">
        <button class="filter-btn active" onclick="filterLenders('All',this)">All</button>
        <button class="filter-btn" onclick="filterLenders('Nationalized',this)">Public Sector</button>
        <button class="filter-btn" onclick="filterLenders('Private',this)">Private Sector</button>
        <button class="filter-btn" onclick="filterLenders('NBFC',this)">NBFC</button>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
        <table class="min-w-[900px] w-full text-sm whitespace-nowrap">
            <thead class="border-b text-gray-500">
                <tr>
                    <th class="text-left py-3">Lender</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">EMI</th>
                    <th class="text-center">Loan</th>
                    <th class="text-center">Tenure</th>
                    <th></th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @foreach($lenders as $lender)
                @php $p = $lender->products[0] ?? null; @endphp
                <tr data-type="{{ $lender->type }}">
                    <td class="py-4 flex items-center gap-3 min-w-[220px]">
                        <div class="w-10 h-10 rounded-full bg-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                            @if(!empty($lender->logo))
                                <img src="{{ $lender->logo_url }}" class="w-full h-full object-contain">
                            @else
                                <span class="text-xs text-gray-400">No Logo</span>
                            @endif
                        </div>

                        <div>
                            <p class="font-semibold">{{ $lender->name }}</p>
                            <p class="text-xs text-gray-500">{{ $lender->type }}</p>
                        </div>
                    </td>

                    <td class="text-center font-semibold">{{ $p['rate'] ?? '-' }}</td>
                    <td class="text-center">{{ $p['emi'] ?? '-' }}</td>
                    <td class="text-center">{{ $p['loan'] ?? '-' }}</td>
                    <td class="text-center">{{ $p['tenure'] ?? '-' }}</td>

                    <td class="text-right flex justify-end gap-2 min-w-[140px]">
                        <button class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded text-sm">
                            Select
                        </button>

                        <button
                            onclick='openEditLender({
                                id: "{{ $lender->_id }}",
                                name: @json($lender->name),
                                type: @json($lender->type),
                                products: @json($lender->products)
                            })'
                            class="p-2 rounded-lg hover:bg-gray-100 text-indigo-600">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>

                        <form method="POST" action="{{ route('lenders.destroy',$lender->_id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this lender?')"
                                    class="p-2 rounded hover:bg-red-100 text-red-600">
                                🗑
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL ================= --}}
<div id="lenderModal"
     class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center px-3">

    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg max-h-[90vh] overflow-y-auto">

        <form method="POST"
              id="lenderForm"
              action="{{ route('lenders.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="flex justify-between items-center p-4 sm:p-5 border-b">
                <h3 id="modalTitle" class="text-base sm:text-lg font-semibold">Add Lender</h3>
                <button type="button" onclick="closeLenderModal()">✕</button>
            </div>

            <div class="p-4 sm:p-5 space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input name="name" class="input" placeholder="Lender Name" required>
                    <select name="type" class="input">
                        <option>Nationalized</option>
                        <option>Private</option>
                        <option>NBFC</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Bank Logo</label>
                    <input type="file" name="logo" class="input">
                </div>

                <div id="productsContainer"></div>

                <button type="button"
                        onclick="addProduct()"
                        class="w-full border border-dashed rounded-lg py-2 text-indigo-600">
                    + Add Another Product
                </button>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 p-4 border-t">
                <button type="button"
                        onclick="closeLenderModal()"
                        class="px-4 py-2 bg-gray-100 rounded">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= STYLES ================= --}}
<style>
.input{
    width:100%;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:10px
}
.product-box{
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:16px;
    margin-top:16px
}
.filter-btn{
    padding:6px 14px;
    border-radius:999px;
    background:#f3f4f6;
    font-size:14px;
    cursor:pointer;
    color:#374151;
}
.filter-btn.active{
    background:#111827;
    color:white;
}
</style>

{{-- ================= JS (UNCHANGED) ================= --}}
<script>
let productCount = 0;

function filterLenders(type,btn){
    document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('tbody tr').forEach(row=>{
        row.style.display = (type === 'All' || row.dataset.type === type) ? '' : 'none';
    });
}

function openAddLender(){
    resetModal();

    lenderForm.action = "{{ route('lenders.store') }}";
    modalTitle.innerText = 'Add Lender';

    addProduct();
    lenderModal.classList.remove('hidden');
}

function openEditLender(lender){
    resetModal();

    lenderForm.action = `/lenders/${lender.id}/update`;
    modalTitle.innerText = 'Edit Lender';

    document.querySelector('[name=name]').value = lender.name ?? '';
    document.querySelector('[name=type]').value = lender.type ?? '';

    if (Array.isArray(lender.products) && lender.products.length > 0) {
        lender.products.forEach(p => {
            addProduct();

            let box = document.querySelectorAll('.product-box')[productCount - 1];

            box.querySelector('[name="product_name[]"]').value =
                p.product_name ?? p.name ?? '';

            box.querySelector('[name="rate[]"]').value = p.rate ?? '';
            box.querySelector('[name="emi[]"]').value = p.emi ?? '';
            box.querySelector('[name="loan[]"]').value = p.loan ?? '';
            box.querySelector('[name="tenure[]"]').value = p.tenure ?? '';
        });
    }

    lenderModal.classList.remove('hidden');
}

function closeLenderModal(){
    lenderModal.classList.add('hidden');
}

function addProduct(){
    productCount++;
    productsContainer.insertAdjacentHTML('beforeend',`
        <div class="product-box">
            <h4 class="font-semibold mb-3">Loan Product #${productCount}</h4>
            <input name="product_name[]" class="input mb-3" placeholder="Home Loan">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input name="rate[]" class="input" placeholder="Rate">
                <input name="emi[]" class="input" placeholder="EMI">
                <input name="loan[]" class="input" placeholder="Loan Amount">
                <input name="tenure[]" class="input" placeholder="Tenure">
            </div>
        </div>
    `);
}

function resetModal(){
    document.querySelectorAll('#lenderModal input').forEach(i=>{
        if(i.type !== 'file') i.value = '';
    });

    productsContainer.innerHTML = '';
    productCount = 0;
}
</script>

@endsection
