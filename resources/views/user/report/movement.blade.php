@extends('layouts.user')

@section('title', "Pergerakan Stok")

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

<div class="p-8 mobile:p-4 flex flex-col gap-8">
    {{-- <div class="bg-white rounded-lg p-2 flex items-center gap-4 px-4">
        <div class="flex flex-col grow pt-1">
            <div class="text-xs text-slate-500">Rentang Tanggal</div>
            <input type="text" id="dateRangePicker" class="h-10 outline-0 text-sm text-slate-600 w-full">
        </div>
        <button class="bg-green-500 text-xs text-white font-medium p-3 px-4 rounded-lg" onclick="addFilter({download: 1})">
            Download Excel
        </button>
    </div> --}}

    <div class="bg-white rounded-lg p-4">
        <div class="py-2 flex mobile:flex-col items-end gap-4">
            <div class="flex flex-col border rounded-lg p-2 w-4/12 mobile:w-full">
                <div class="text-xs text-slate-500">Cabang</div>
                <select class="w-full cursor-pointer h-10 text-sm text-slate-600 outline-0" onchange="addFilter({branch_id: this.value})">
                    <option value="">Semua Cabang</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $request->branch_id == $branch->id ? "selected='selected'" : "" }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col border rounded-lg p-2 w-4/12 mobile:w-full">
                <div class="text-xs text-slate-500">Rentang Tanggal</div>
                <div class="flex items-center">
                    <input type="text" id="dateRangePicker" class="h-10 outline-0 text-sm text-slate-600 w-full">
                    <ion-icon name="chevron-down-outline"></ion-icon>
                </div>
            </div>
            <div class="flex items-center w-4/12 mobile:w-full justify-end">
                <button class="bg-green-500 text-sm text-white font-medium h-12 rounded-lg px-8" onclick="addFilter({download: 1})">
                    Download Excel
                </button>
            </div>
        </div>
        <!-- Outer scroll container -->
        <div class="overflow-x-auto mt-2">
            <!-- Inner scrollable width wrapper -->
            <div class="w-max min-w-full mt-0 space-y-1">
                <!-- Header -->
                <div class="flex bg-slate-100 text-slate-600 text-sm font-semibold px-4 py-2 rounded min-w-full">
                    <div class="w-56 flex items-center gap-4">
                        <ion-icon name="cube-outline"></ion-icon>
                        <div>Produk</div>
                    </div>
                    <div class="w-48">Cabang</div>
                    <div class="w-32">Stok Aktual</div>
                    <div class="w-32">Masuk</div>
                    <div class="w-32">Keluar</div>
                    <div class="w-32">Opname</div>
                    <div class="w-32"></div>
                </div>

                <!-- Rows -->
                @foreach ($products as $product)
                    <div class="flex bg-white text-slate-700 text-sm px-4 py-2 rounded shadow-sm min-w-full">
                        <div class="w-56 flex items-center gap-4">
                            @if (@$product->images->count() == 0)
                                <div class="min-w-12 h-12 rounded-lg flex items-center justify-center bg-slate-200">
                                    <ion-icon name="image-outline"></ion-icon>
                                </div>
                            @else
                                <img 
                                    src="{{ asset('storage/product_images/' . @$product->images[0]->filename) }}"
                                    class="h-12 w-12 rounded-lg object-cover"
                                    alt="{{ $product->name }}"
                                />
                            @endif
                            <div class="text-slate-600">{{ $product->name }}</div>
                        </div>
                        <div class="w-48 flex items-center ">
                            {{ $product->branch->name }}
                        </div>
                        <div class="w-32 flex items-center">{{ $product->quantity }}</div>
                        <div class="w-32 flex items-center">{{ $product->movements['inbound'] }}</div>
                        <div class="w-32 flex items-center">{{ $product->movements['outbound'] }}</div>
                        <div class="w-32 flex items-center">{{ $product->movements['opname'] }}</div>
                        <div class="w-32 flex items-center">
                            <a href="{{ route('movement_report.detail', [
                                'productID' => $product->id,
                                'start_date' => $startDate,
                                'end_date' => $endDate
                            ]) }}" class="p-2 px-3 rounded-lg bg-primary text-white">
                                <ion-icon name="bar-chart-outline"></ion-icon>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
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