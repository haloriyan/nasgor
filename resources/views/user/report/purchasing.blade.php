@extends('layouts.user')

@section('title', "Laporan Pembelian")

@php
    use Carbon\Carbon;
@endphp
    
@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
@endsection

@section('content')
<input type="hidden" id="startDate" value="{{ $startDate }}">
<input type="hidden" id="endDate" value="{{ $endDate }}">

<div class="p-8 flex flex-col gap-8">
    <div class="bg-white rounded-lg p-2 flex items-center gap-4 px-4">
        <div class="flex flex-col border rounded-lg p-2 grow mobile:w-full">
            <div class="text-xs text-slate-500">Cari No. Pembelian</div>
            <form class="flex items-center gap-4" onsubmit="searchProduct(event)">
                <button class="flex items-center">
                    <ion-icon name="search-outline" class="text-lg text-slate-700"></ion-icon>
                </button>
                <input type="text" id="q" name="q" class="h-8 outline-0 text-xs text-slate-600 w-full" value="{{ $request->q }}">
                @if ($request->q != "")
                    <div class="flex items-center cursor-pointer" onclick="addFilter({q: null})">
                        <ion-icon name="close-outline" class="text-red-500 text-lg"></ion-icon>
                    </div>
                @endif
            </form>
        </div>
        <div class="flex flex-col border rounded-lg p-2 w-3/12 mobile:w-full">
            <div class="text-xs text-slate-500">Cabang</div>
            <select class="w-full cursor-pointer h-8 text-xs text-slate-600 outline-0" onchange="addFilter({branch_id: this.value})">
                <option value="">Semua Cabang</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $request->branch_id == $branch->id ? "selected='selected'" : "" }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col border rounded-lg p-2 w-3/12 mobile:w-full">
            <div class="text-xs text-slate-500">Rentang Tanggal</div>
            <div class="flex items-center">
                <input type="text" id="dateRangePicker" class="h-8 outline-0 text-xs text-slate-600 w-full">
                <ion-icon name="chevron-down-outline"></ion-icon>
            </div>
        </div>
        <button class="h-12 w-12 flex items-center justify-center rounded-lg bg-green-500 text-white" onclick="addFilter({download: 1})">
            <ion-icon name="download-outline" class="text-2xl"></ion-icon>
        </button>
    </div>

    <div class="bg-white rounded-lg p-4">
        <!-- Outer scroll container -->
        <div class="overflow-x-auto mt-2">
            <!-- Inner scrollable width wrapper -->
            <div class="w-max min-w-full mt-0 space-y-1">
                <!-- Header -->
                <div class="flex bg-slate-100 text-slate-600 text-sm font-semibold px-4 py-2 rounded min-w-full">
                    <div class="w-32">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <div class="w-48">No. Pembelian</div>
                    <div class="w-36">Cabang</div>
                    <div class="w-36">Staff</div>
                    <div class="w-36">Supplier</div>
                    <div class="w-40">Produk</div>
                    <div class="w-24">Harga</div>
                    <div class="w-24">Qty</div>
                    <div class="w-28">Subtotal</div>
                    <div class="w-28">Total Qty</div>
                    <div class="w-32">Total Harga</div>
                    <div class="w-36">Penerima</div>
                    <div class="w-36"></div>
                </div>

                <!-- Rows -->
                @foreach ($purchasings as $purchase)
                    @foreach ($purchase->items as $i => $item)
                        <div class="flex bg-white text-slate-700 text-sm px-4 py-2 rounded shadow-sm min-w-full">
                            @if ($i === 0)
                                <div class="w-32">
                                    <div>{{ Carbon::parse($purchase->created_at)->isoFormat('DD MMMM YYYY') }}</div>
                                    <div class="text-xs text-slate-500">{{ Carbon::parse($purchase->created_at)->isoFormat('HH:mm') }}</div>
                                </div>
                                <div class="w-48">{{ @$purchase->label }}</div>
                                <div class="w-36">{{ @$purchase->branch->name }}</div>
                                <div class="w-36">{{ @$purchase->creator->name }}</div>
                                <div class="w-36">{{ @$purchase->supplier->name }}</div>
                            @else
                                <div class="w-32"></div>
                                <div class="w-48"></div>
                                <div class="w-36"></div>
                                <div class="w-36"></div>
                                <div class="w-36"></div>
                            @endif

                            <div class="w-40">{{ $item->product->name }}</div>
                            <div class="w-24">{{ currency_encode($item->price) }}</div>
                            <div class="w-24">{{ $item->quantity }}</div>
                            <div class="w-28">{{ currency_encode($item->total_price) }}</div>

                            @if ($i === 0)
                                <div class="w-28">{{ @$purchase->total_quantity }}</div>
                                <div class="w-32">{{ currency_encode(@$purchase->total_price) }}</div>
                                <div class="w-36">{{ @$purchase->receiver->name }}</div>
                                <div class="w-32">
                                    <div>{{ Carbon::parse($purchase->received_at)->isoFormat('DD MMMM YYYY') }}</div>
                                    <div class="text-xs text-slate-500">{{ Carbon::parse($purchase->received_at)->isoFormat('HH:mm') }}</div>
                                </div>
                            @else
                                <div class="w-28"></div>
                                <div class="w-32"></div>
                                <div class="w-36"></div>
                                <div class="w-36"></div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    @if ($purchasings->hasMorePages())
        <div class="bg-white rounded-lg p-4 shadow shadow-slate-200">
            {{ $purchasings->links() }}
        </div>
    @endif

</div>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

<script>
    const startDate = select("#startDate").value;
    const endDate = select("#endDate").value;

    flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        locale: {
            rangeSeparator: " ke ",
        },
        onChange: selectedDates => {
            if (selectedDates.length === 2) {
                const [start, end] = selectedDates;
                addFilter({
                    start_date: dayjs(start).format('YYYY-MM-DD'),
                    end_date: dayjs(end).format('YYYY-MM-DD'),
                });
            }
        }
    });
</script>
@endsection