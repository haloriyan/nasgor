@extends('layouts.user')

@section('title', "Penjualan")
    
@section('content')
<div class="flex flex-col gap-4 bg-white m-8 p-8 rounded-lg shadow shadow-slate-200 mt-8">
    <div class="flex items-end gap-8">
        <form class="group border focus-within:border-primary rounded-lg p-2 relative flex flex-col grow">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cari invoice atau nama pelanggan</label>
            <div class="flex items-center gap-2">
                <input type="text" name="q" id="q" class="w-full h-10 mt-3 outline-none bg-transparent text-sm text-slate-700" required value="{{ $request->q }}" />
                @if ($request->q != "")
                    <a href="?q=" class="text-red-500">
                        <ion-icon name="close-outline" class="text-lg"></ion-icon>
                    </a>
                @endif
                <button>
                    <ion-icon name="search-outline" class="text-slate-700 text-lg"></ion-icon>
                </button>
            </div>
        </form>
        <div class="mobile:hidden">
            <div class="text-xs text-slate-500">Status Pembayaran</div>
            <div class="flex p-2 bg-slate-200 rounded-lg mt-1">
                <div class="p-2 px-6 rounded-lg cursor-pointer text-xs {{ $request->payment_status == "UNPAID" ? 'bg-white text-primary' : 'text-slate-500' }}" onclick="addFilter('payment_status', 'UNPAID')">
                    BELUM
                </div>
                <div class="p-2 px-6 rounded-lg cursor-pointer text-xs {{ $request->payment_status == "" ? 'bg-white text-primary font-medium' : 'text-slate-500' }}" onclick="addFilter('payment_status', '')">
                    SEMUA
                </div>
                <div class="p-2 px-6 rounded-lg cursor-pointer text-xs {{ $request->payment_status == "PAID" ? 'bg-white text-primary font-medium' : 'text-slate-500' }}" onclick="addFilter('payment_status', 'PAID')">
                    LUNAS
                </div>
            </div>
        </div>
        <button class="bg-green-500 text-sm text-white font-medium h-12 px-4 rounded-lg flex items-center" onclick="toggleHidden('#Create')">
            <ion-icon name="add-outline" class="text-lg"></ion-icon>
        </button>
    </div>
    <div class="min-w-full overflow-hidden overflow-x-auto mt-2">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">No. Invoice</th>
                    <th scope="col" class="px-6 py-3 text-left">Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left">Total</th>
                    <th scope="col" class="px-6 py-3 text-left">Status</th>
                    <th scope="col" class="px-6 py-3 text-left">
                        <ion-icon name="create-outline"></ion-icon>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($sales as $sale)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <a href="{{ route('sales.detail', $sale->id) }}" class="text-primary font-medium">
                                {{ $sale->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            @if ($sale->customer->id == null)
                                -
                            @else
                                {{ $sale->customer->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ currency_encode($sale->total_price) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="p-1 px-3 rounded-full text-xs font-medium {{ $sale->status != "DRAFT" ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $sale->status != "DRAFT" ? "TERBIT" : "DRAFT" }}
                                </div>
                                @if ($sale->status != "DRAFT")
                                    <div class="p-1 px-3 rounded-full text-xs  font-medium {{ $sale->payment_status == "PAID" ? 'bg-green-500 text-white' : 'bg-red-100 text-red-500' }}">
                                        {{ $sale->payment_status == "PAID" ? "Lunas" : "Belum Bayar" }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $sale->notes }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('ModalArea')
    
@include('user.sales.create')

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    new MultiSelectorAPI('#CustomerSelector', [], {
        fetchUrl: '/api/customer/search?branch_id={{ $me->access->branch_id }}&q=',
        name: "customer_id",
        label: "Pelanggan",
        single: true,
        parseResponse: (data) => data.customers // if the response is { categories: [...] }
    });
</script>
@endsection