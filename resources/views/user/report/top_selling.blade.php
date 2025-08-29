@extends('layouts.user')

@section('title', "Paling Laku")
    
@section('content')
<input type="hidden" id="chartOptions" value="{{ json_encode($chartOptions) }}">
<div class="p-8 flex flex-col gap-8">
    <div class="flex items-center gap-4">
        <div class="flex grow">
            <a href="{{ url()->previous() }}" class="flex items-center gap-3">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <div class="text-xs text-slate-500">Kembali</div>
            </a>
        </div>
        <select name="branch_id" id="branch_id" class="text-xs text-slate-600 font-medium border rounded-lg p-3 px-3" onchange="addFilter('branch_id', this.value)">
            <option value="">Semua Cabang</option>
            @foreach ($me->branches as $b => $branch)
                <option value="{{ $branch->id }}" {{ $branchID == $branch->id ? "selected='selected'" : "" }}>{{ $branch->name }}</option>
            @endforeach
        </select>
        <select name="date_range" id="date_range" class="text-xs text-slate-600 font-medium border rounded-lg p-3 px-3" onchange="addFilter('date_range', this.value)">
            @foreach (config('report_date_ranges') as $key => $label)
                <option value="{{ $key }}" {{ $request->date_range == $key ? "selected='selected'" : "" }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-8 flex gap-8">
        <div class="w-5/12">
            <div id="Chart" class="w-full aspect-square"></div>
        </div>
        <div class="flex flex-col gap-4 grow">
            @foreach ($topProducts as $item)
                <div class="flex items-center gap-2">
                    <div class="flex flex-col gap-1 grow">
                        <div class="text-sm text-slate-600">{{ $item['product_name'] }}</div>
                        <div class="text-xs text-red-500">{{ currency_encode($item['total_sales']) }}</div>
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $item['total_qty'] }} pieces
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.6.0/dist/echarts.min.js" integrity="sha256-v0oiNSTkC3fDBL7GfhIiz1UfFIgM9Cxp3ARlWOEcB7E=" crossorigin="anonymous"></script>
<script>
    const chartOptions = JSON.parse(select("#chartOptions").value);
    const paymentSummaryChart = echarts.init(
        select("#Chart")
    );

    paymentSummaryChart.setOption(chartOptions);
</script>
@endsection