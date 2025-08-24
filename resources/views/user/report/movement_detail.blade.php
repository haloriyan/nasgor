@extends('layouts.user')

@section('title', "Detail Pergerakan Stok")

@php
    use Carbon\Carbon;
    // $movements = array_reverse($movements);
@endphp

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
@endsection
    
@section('content')
<input type="hidden" id="MovementChartConfig" value="{{ json_encode($MovementChartConfig) }}">
<input type="hidden" id="InboundChartConfig" value="{{ json_encode($InboundChartConfig) }}">
<input type="hidden" id="OutboundChartConfig" value="{{ json_encode($OutboundChartConfig) }}">
<input type="hidden" id="startDate" value="{{ $startDate }}">
<input type="hidden" id="endDate" value="{{ $endDate }}">

<div class="p-8 flex flex-col gap-8">
    <div class="flex items-center gap-12 mobile:gap-6 bg-white rounded-lg shadow shadow-slate-200 p-8 mobile:p-4">
        <a href="{{ route('movement_report') }}" class="flex items-center">
            <ion-icon name="arrow-back-outline" class="text-lg text-slate-600"></ion-icon>
        </a>
        <div class="flex flex-col gap-1">
            <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                <ion-icon name="cube-outline"></ion-icon>
                <div class="mobile:hidden">Pergerakan Stok</div>
            </div>
            <h2 class="text-lg mobile:text-sm text-slate-700">
                {{ $product->name }}
            </h2>
        </div>
        <div class="flex flex-col gap-1 grow">
            <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                <ion-icon name="storefront-outline"></ion-icon>
                Cabang
            </div>
            <h2 class="text-lg mobile:text-sm text-slate-700">
                {{ $product->branch->name }}
            </h2>
        </div>
        <div class="flex flex-col w-3/12 mobile:hidden">
            <div class="text-xs text-slate-500">Rentang Tanggal</div>
            <div class="flex items-center">
                <input type="text" id="dateRangePicker" class="h-10 outline-0 text-sm text-slate-600 w-full">
                <ion-icon name="chevron-down-outline" class="text-slate-700"></ion-icon>
            </div>
        </div>
        <button class="h-12 w-12 flex items-center justify-center rounded-lg bg-green-500 text-white" onclick="addFilter({download: 1})">
            <ion-icon name="download-outline" class="text-2xl"></ion-icon>
        </button>
    </div>

    <div class="desktop:hidden p-4 bg-white rounded-lg shadow">
        <div class="text-xs text-slate-500">Rentang Tanggal</div>
        <div class="flex items-center">
            <input type="text" id="dateRangePicker" class="h-10 outline-0 text-sm text-slate-600 w-full">
            <ion-icon name="chevron-down-outline" class="text-slate-700"></ion-icon>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow- shadow-slate-200 p-8">
        <div id="MovementChart" class="w-full h-[300px] mb-8"></div>

        <table class="table w-full">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left font-normal">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </th>
                    <th class="p-2 text-left font-normal text-sm">
                        Tipe
                    </th>
                    <th class="p-2 text-left font-normal text-sm">
                        Stok Lama
                    </th>
                    <th class="p-2 text-left font-normal text-sm">
                        Stok Bergerak
                    </th>
                    <th class="p-2 text-left font-normal text-sm">
                        Stok Baru
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $m => $movement)
                    @php
                        // $stokLama = @$movements[$m - 1]['quantity'] + $movement['movement_amount'];
                        $stokLama = $movement['quantity'];
                        $stokBaru = 0;
                        if ($movement['type'] == "inbound") {
                            $stokBaru = $stokLama + $movement['quantity'];
                        } else if ($movement['type'] == "outbound") {
                            $stokBaru = $stokLama - $movement['movement_amount'];
                        }
                    @endphp
                    <tr>
                        <td class="p-2 text-sm">
                            {{ Carbon::parse($movement['date'])->isoFormat('DD MMMM YYYY, HH:mm:ss') }}
                        </td>
                        <td class="p-2 text-sm flex items-center">
                            <div class="text-xs p-1 px-3 rounded-full {{ $movement['type'] == 'inbound' ? 'bg-green-500 text-white' : '' }} {{ $movement['type'] == 'outbound' ? 'bg-red-500 text-white' : '' }} {{ $movement['type'] == 'opname' ? 'bg-slate-200 text-slate-700' : '' }}">
                                @if ($movement['type'] == "inbound")
                                    Masuk
                                @endif
                                @if ($movement['type'] == "outbound")
                                    Keluar
                                @endif
                                @if ($movement['type'] == "opname")
                                    Opname
                                @endif
                            </div>
                        </td>
                        <td class="p-2 text-sm">
                            {{ $stokLama }}
                        </td>
                        <td class="p-2 text-sm">
                            {{ @$movement['movement_amount'] }}
                        </td>
                        <td class="p-2 text-sm">
                            {{ $stokBaru }}
                        </td>
                        {{-- <td class="p-2 text-sm">
                            {{ @$movements[$m - 1]['quantity'] }}
                        </td>
                        <td class="p-2 text-sm">
                            {{ @$movements[$m - 1]['movement_amount'] }}
                        </td>
                        <td class="p-2 text-sm">
                            {{ (@$movements[$m - 1]['quantity'] - @$movements[$m - 1]['movement_amount']) }}
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ json_encode($movements) }}

    <div class="grid grid-cols-2 mobile:grid-cols-1 gap-8">
        <div class="bg-white rounded-lg shadow- shadow-slate-200 p-8">
            <div id="InboundChart" class="w-full h-[250px]"></div>
        </div>
        <div class="bg-white rounded-lg shadow- shadow-slate-200 p-8">
            <div id="OutboundChart" class="w-full h-[250px]"></div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.6.0/dist/echarts.min.js" integrity="sha256-v0oiNSTkC3fDBL7GfhIiz1UfFIgM9Cxp3ARlWOEcB7E=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

<script>
    const MovementChartConfig = JSON.parse(select("#MovementChartConfig").value);
    const InboundChartConfig = JSON.parse(select("#InboundChartConfig").value);
    const OutboundChartConfig = JSON.parse(select("#OutboundChartConfig").value);
    const startDate = select("#startDate").value;
    const endDate = select("#endDate").value;
    
    const MovementChart = echarts.init(
        select("#MovementChart")
    );
    const InboundChart = echarts.init(
        select("#InboundChart")
    );
    const OutboundChart = echarts.init(
        select("#OutboundChart")
    );

    MovementChart.setOption(MovementChartConfig);
    InboundChart.setOption(InboundChartConfig);
    OutboundChart.setOption(OutboundChartConfig);

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
                let filter = {
                    start_date: dayjs(start).format('YYYY-MM-DD'),
                    end_date: dayjs(end).format('YYYY-MM-DD'),
                };
                console.log(filter);
                addFilter(filter);
            }
        }
    });
</script>
@endsection