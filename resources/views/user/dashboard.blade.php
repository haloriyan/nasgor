@extends('layouts.user')

@section('title', "Dashboard")

@php
    $availableDateRanges = [
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

<div class="flex items-center justify-end gap-4 p-8 pb-0">
    <div class="flex flex-col grow gap-2 mobile:hidden">
        <h2 class="text-xl text-slate-700 font-medium">Performa untuk Cabangmu</h2>
        <div class="flex items-center gap-2">
            @foreach ($myBranches as $myBranch)
                <div class="p-1 px-3 text-xs text-slate-600 bg-white border rounded">
                    {{ $myBranch->name }}
                </div>
            @endforeach
        </div>
    </div>
    <div class="text-xs">Rentang Waktu</div>
    <select name="date_range" id="date_range" class="text-xs text-slate-600 font-medium border rounded-lg p-3 px-3" onchange="addFilter('date_range', this.value)">
        @foreach ($availableDateRanges as $key => $label)
            <option value="{{ $key }}" {{ $request->date_range == $key ? "selected='selected'" : "" }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="grid grid-cols-4 mobile:grid-cols-1 gap-4 p-8 pb-0">
    <div class="flex flex-col border rounded-lg bg-white">
        <div class="p-8">
            <div class="text-xl text-slate-700 font-medium">
                {{ currency_encode($omset) }}
            </div>
        </div>
        <a href="{{ route('sales') }}" class="flex items-center gap-4 p-4 px-8 bg-green-100 text-green-500 rounded-b-lg">
            <ion-icon name="cash-outline" class="text-lg"></ion-icon>
            <div class="flex grow text-xs">Omset</div>
            <ion-icon name="arrow-forward-outline" class="text-lg"></ion-icon>
        </a>
    </div>
    <div class="flex flex-col border rounded-lg bg-white">
        <div class="p-8">
            <div class="text-xl text-slate-700 font-medium">
                {{ $volume }}
            </div>
        </div>
        <a href="{{ route('sales') }}" class="flex items-center gap-4 p-4 px-8 bg-blue-100 text-blue-500 rounded-b-lg">
            <ion-icon name="bar-chart-outline" class="text-lg"></ion-icon>
            <div class="flex grow text-xs">Transaksi</div>
            <ion-icon name="arrow-forward-outline" class="text-lg"></ion-icon>
        </a>
    </div>
    <div class="flex flex-col border rounded-lg bg-white">
        <div class="p-8">
            <div class="text-xl text-slate-700 font-medium">
                {{ $newCustomersCount }}
            </div>
        </div>
        <a href="{{ route('customer') }}" class="flex items-center gap-4 p-4 px-8 bg-purple-100 text-purple-500 rounded-b-lg">
            <ion-icon name="people-outline" class="text-lg"></ion-icon>
            <div class="flex grow text-xs">Pelanggan Baru</div>
            <ion-icon name="arrow-forward-outline" class="text-lg"></ion-icon>
        </a>
    </div>
    <div class="flex flex-col border rounded-lg bg-white">
        <div class="p-8">
            <div class="text-xl text-slate-700 font-medium">
                {{ $lowStocks->count() }}
            </div>
        </div>
        <a href="{{ route('product') }}" class="flex items-center gap-4 p-4 px-8 bg-red-100 text-red-500 rounded-b-lg">
            <ion-icon name="cube-outline" class="text-lg"></ion-icon>
            <div class="flex grow text-xs">Hampir Habis</div>
            <ion-icon name="arrow-forward-outline" class="text-lg"></ion-icon>
        </a>
    </div>
</div>
<div class="grid grid-cols-2 mobile:grid-cols-1 gap-8 p-8 pb-0">
    <div class="flex flex-col gap-8 grow">
        <div class="bg-white rounded-lg border">
            <div class="flex items-center gap-4 p-6 px-8 border-b">
                <ion-icon name="cash-outline" class="text-2xl text-slate-700"></ion-icon>
                <div class="text-slate-700 flex grow">Omset</div>
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

<div class="grid grid-cols-3 mobile:grid-cols-1 gap-8 p-8">
    <div class="flex flex-col gap-8 grow">
        <div class="bg-white rounded-lg border">
            <div class="flex items-center gap-4 p-6 px-8 border-b">
                <ion-icon name="cube-outline" class="text-2xl text-slate-700"></ion-icon>
                <div class="text-slate-700 flex grow">Stok Menipis</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                @if (count($lowStocks) == 0)
                    <div class="text-sm text-slate-600">Semua produk masih banyak :)</div>
                @endif
                @foreach ($lowStocks as $product)
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-slate-600 flex grow">{{ $product->name }}</div>
                        <div class="text-xs text-slate-500">Stok : {{ $product->quantity }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-8 grow">
        <div class="bg-white rounded-lg border">
            <div class="flex items-center gap-4 p-6 px-8 border-b">
                <ion-icon name="bag-outline" class="text-2xl text-slate-700"></ion-icon>
                <div class="text-slate-700 flex grow">Penjualan</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                @foreach ($sales as $sale)
                    @php
                        $theColor = $colors[rand(0, count($colors) - 1)];
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg text-white bg-{{ $theColor }}-500">
                            <ion-icon name="person-outline"></ion-icon>
                        </div>
                        <div class="text-sm text-slate-600 flex grow">{{ $sale->customer->name }}</div>
                        <div class="text-xs text-slate-500">{{ currency_encode($sale->total_price) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-8 grow">
        <div class="bg-white rounded-lg border">
            <div class="flex items-center gap-4 p-6 px-8 border-b">
                <ion-icon name="chatbubbles-outline" class="text-2xl text-slate-700"></ion-icon>
                <div class="text-slate-700 flex grow">Ulasan</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                @foreach ($reviews as $r => $review)
                    <div class="flex flex-col gap-4 ReviewItem {{ $r != 0 ? 'hidden' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="text-sm text-slate-400 font-medium flex grow">{{ $review->customer->name }}</div>
                            <div class="flex items-center gap-2">
                                @for ($i = 0; $i < 5; $i++)
                                    <ion-icon name="star" class="{{ $review->rating <= $i ? 'text-slate-500' : 'text-yellow-500' }}"></ion-icon>
                                @endfor
                            </div>
                        </div>
                        <div class="text-xs text-slate-600">{{ $review->body }}</div>
                    </div>
                @endforeach
                <div class="flex items-center gap-4 mt-4">
                    <div class="flex items-center justify-center gap-4 grow">
                        @foreach ($reviews as $r => $review)
                            <div class="ReviewDot w-3 h-3 border cursor-pointer border-primary {{ $r == 0 ? 'bg-primary' : '' }} rounded-full" onclick="ViewReview({{ $r }})"></div>
                        @endforeach
                    </div>
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

    omsetChart.setOption(omsetChartOption);
    volumeChart.setOption(volumeChartOption);

    let reviewIndex = 0;
    let reviewItems = selectAll(".ReviewItem");
    let reviewDots = selectAll(".ReviewDot");
    
    const renderReview = () => {
        reviewItems.forEach(item => item.classList.add('hidden'));
        reviewItems[reviewIndex].classList.remove('hidden');

        reviewDots.forEach(dot => dot.classList.remove('bg-primary'));
        reviewDots[reviewIndex].classList.add('bg-primary')
    }
    const handleReviewInterval = () => {
        if (reviewIndex + 1 === reviewItems.length) {
            reviewIndex = 0;
        } else {
            reviewIndex++;
        }
        renderReview();
    };
    let reviewInterval = setInterval(handleReviewInterval, 6000);

    const ViewReview = (index) => {
        reviewIndex = index;
        renderReview();
        clearInterval(reviewInterval);
        let reviewInterval = setInterval(handleReviewInterval, 6000);
    }
    
</script>
@endsection