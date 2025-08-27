@extends('layouts.user')

@section('title', "Laporan Penjualan")
    
@php
    $availableDateRanges = [
        'today' => "Hari Ini",
        'last_7_days' => "Minggu Ini",
        'this_month' => "Bulan Ini",
        'last_3_months' => "3 Bulan Terakhir",
        'last_6_months' => "6 Bulan Terakhir",
        'this_year' => "Tahun Ini",
        'last_2_years' => "2 Tahun Terakhir"
    ];
    $colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];
@endphp

@section('content')
<input type="hidden" id="OmsetChartOption" value="{{ json_encode($omset_chart) }}">
<input type="hidden" id="VolumeChartOption" value="{{ json_encode($volume_chart) }}">
<input type="hidden" id="PaymentSummaryChartOption" value="{{ json_encode($paymentSummaryChart) }}">

<div class="p-8">
    <div class="flex items-center justify-end gap-4">
        <select name="branch_id" id="branch_id" class="text-xs text-slate-600 font-medium border rounded-lg p-3 px-3" onchange="addFilter('branch_id', this.value)">
            <option value="">Semua Cabang</option>
            @foreach ($myBranches as $b => $branch)
                <option value="{{ $branch->id }}" {{ $branchID == $branch->id ? "selected='selected'" : "" }}>{{ $branch->name }}</option>
            @endforeach
        </select>
        <select name="date_range" id="date_range" class="text-xs text-slate-600 font-medium border rounded-lg p-3 px-3" onchange="addFilter('date_range', this.value)">
            @foreach ($availableDateRanges as $key => $label)
                <option value="{{ $key }}" {{ $request->date_range == $key ? "selected='selected'" : "" }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 mobile:grid-cols-1 gap-8 mt-8">
        <div class="flex flex-col gap-8 grow">
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 p-6 px-8 border-b">
                    <ion-icon name="cash-outline" class="text-2xl text-slate-700"></ion-icon>
                    <div class="text-slate-700 flex grow">Penjualan Kotor</div>
                </div>
                <div class="p-8">
                    <div id="OmsetChart" class="w-full h-[350px]"></div>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-8 grow">
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 p-6 px-8 border-b">
                    <ion-icon name="bar-chart-outline" class="text-2xl text-slate-700"></ion-icon>
                    <div class="text-slate-700 flex grow">Volume Transaksi</div>
                </div>
                <div class="p-8">
                    <div id="VolumeChart" class="w-full h-[350px]"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 mobile:grid-cols-1 gap-8 mt-8">
        <div class="flex flex-col gap-6 grow">
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 p-4 px-6 border-b">
                    <ion-icon name="bag-outline" class="text-2xl text-slate-700"></ion-icon>
                    <div class="text-slate-700 text-sm flex grow">Paling Laku</div>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    @foreach ($topProducts as $item)
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col gap-1 grow">
                                <div class="text-sm text-slate-600">{{ $item['product_name'] }}</div>
                                <div class="text-xs text-primary">{{ currency_encode($item['total_sales']) }}</div>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $item['total_qty'] }} pieces
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-8 grow">
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 p-4 px-6 border-b">
                    <ion-icon name="storefront-outline" class="text-2xl text-slate-700"></ion-icon>
                    <div class="text-slate-700 text-sm flex grow">Performa Cabang</div>
                </div>
                <div class="p-6">
                    @foreach ($branchPerformance as $item)
                        <div class="flex items-center gap-2 cursor-pointer" onclick="addFilter('branch_id', '{{ $item['branch_id'] }}')">
                            <div class="flex flex-col gap-1 grow">
                                <div class="text-sm text-slate-600">{{ $item['branch']->name }}</div>
                                <div class="text-xs text-primary">{{ currency_encode($item['total_sales']) }}</div>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $item['transaction_count'] }} transaksi
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-8 grow">
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 p-4 px-6 border-b">
                    <ion-icon name="bar-chart-outline" class="text-2xl text-slate-700"></ion-icon>
                    <div class="text-slate-700 text-sm flex grow">Metode Pembayaran</div>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    <div id="PaymentSummaryChart" class="w-full h-[350px]"></div>
                    @foreach ($paymentSummary as $item)
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col gap-1 grow">
                                <div class="text-sm text-slate-600">{{ $item['name'] ?? "Tidak Diketahui" }}</div>
                                <div class="text-xs text-primary">{{ currency_encode($item['total_amount']) }}</div>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $item['value'] }} trx
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.6.0/dist/echarts.min.js" integrity="sha256-v0oiNSTkC3fDBL7GfhIiz1UfFIgM9Cxp3ARlWOEcB7E=" crossorigin="anonymous"></script>
<script>
    const omsetChartOption = JSON.parse(select("#OmsetChartOption").value);
    const omsetChart = echarts.init(
        select("#OmsetChart")
    );
    const volumeChartOption = JSON.parse(select("#VolumeChartOption").value);
    const volumeChart = echarts.init(
        select("#VolumeChart")
    );
    const paymentSummaryChartOption = JSON.parse(select("#PaymentSummaryChartOption").value);
    const paymentSummaryChart = echarts.init(
        select("#PaymentSummaryChart")
    );

    omsetChart.setOption(omsetChartOption);
    volumeChart.setOption(volumeChartOption);
    paymentSummaryChart.setOption(paymentSummaryChartOption);
</script>
@endsection