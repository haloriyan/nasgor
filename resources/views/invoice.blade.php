@extends('layouts.invoice')

@section('title', $sales->invoice_number . " - " . $sales->branch->name)

@php
    use Carbon\Carbon;
    $branch = $sales->branch;
    $customer = $sales->customer;
@endphp
    
@section('content')
@if ($message != "")
    <div class="bg-green-500 text-white text-sm p-4 rounded-lg font-medium mt-8 mobile:m-8">
        {{ $message }}
    </div>
@endif
<div class="bg-white rounded-lg mt-4 mobile:m-4 border">
    <div class="flex items-center gap-4 p-8 py-6 border-b">
        <div class="flex flex-col gap-1 grow">
            <h1 class="text-2xl text-slate-700 font-medium">Invoice</h1>
            <div class="text-xs text-slate-500">
                No. Invoice : {{ $sales->invoice_number }}
            </div>
        </div>
        @if ($sales->branch->icon != null)
            <img 
                src="{{ asset('storage/branch_icons/' . $branch->icon) }}" 
                alt="{{ $branch->name }}"
                class="w-14 h-14 rounded-lg"
            >
        @endif
    </div>
    <div class="grid grid-cols-2 gap-6 p-8">
        <div class="flex flex-col gap-1">
            <div class="text-xs text-slate-400">Diterbitkan oleh</div>
            <div class="text-slate-700 font-medium mt-1">
                {{ env('APP_NAME') }} - {{ $branch->name }}
            </div>
            <div class="text-xs text-slate-500">{{ $branch->address }}</div>
        </div>
        <div class="flex flex-col gap-1">
            <div class="text-xs text-slate-400">Ditagihkan kepada</div>
            <div class="text-slate-700 font-medium mt-1">
                {{ $customer->name }}
            </div>
            @if ($customer->phone != null)
                <div class="text-xs text-slate-500">{{ $customer->phone }}</div>
            @endif
        </div>
    </div>
    <div class="grid grid-cols-2 gap-6 p-8 pt-0">
        <div class="flex flex-col gap-1"></div>
        <div class="flex flex-col gap-1">
            <div class="text-xs text-slate-400">Tanggal</div>
            <div class="text-slate-700 font-medium mt-1">
                {{ Carbon::parse($sales->created_at)->isoFormat('DD MMMM YYYY - HH:mm') }}
            </div>
        </div>
    </div>
    <div class="p-8 border-t">
        <div class="text-xs text-slate-400">Detail</div>
        <div class="min-w-full overflow-hidden overflow-x-auto mt-2">
            <table class="table min-w-full divide-y mt-4">
                <thead>
                    <tr class="border-b">
                        <td class="p-2 px-0 text-sm text-slate-700 font-medium">Produk</td>
                        <td class="p-2 text-sm text-slate-700 font-medium">Harga</td>
                        <td class="p-2 text-sm text-slate-700 font-medium">Qty</td>
                        <td class="p-2 text-sm text-slate-700 font-medium">Total</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales->items as $item)
                        <tr>
                            <td class="py-4 text-sm text-slate-700">
                                <div class="flex items-start gap-4">
                                    <img 
                                        src="{{ asset('storage/product_images/' . $item->product->images[0]->filename) }}" 
                                        alt="{{ $item->id }}"
                                        class="w-16 h-16 rounded-lg object-cover mobile:hidden"
                                    >
                                    <div class="flex flex-col gap-2">
                                        <div class="text-sm text-slate-600 font-medium">{{ $item->product->name }}</div>
                                        <div class="flex flex-col gap-1">
                                            @foreach ($item->addons as $add)
                                                <div class="text-xs text-slate-500">+ {{ $add->quantity }} {{ $add->addon->name }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 py-4 text-sm text-slate-700">
                                <div class="text-sm text-slate-600 font-medium">
                                    {{ currency_encode($item->total_price) }}
                                </div>
                                @if ($item->additional_price > 0)
                                    <div class="text-xs text-slate-500 mt-2">
                                        + {{ currency_encode($item->additional_price) }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-2 py-4 text-sm text-slate-700">
                                {{ $item->quantity }}
                            </td>
                            <td class="p-2 py-4 text-sm text-slate-700 font-medium">
                                {{ currency_encode($item->grand_total) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="border-t">
                        <td class="py-4 text-sm text-slate-700 font-medium" colspan="2">Total</td>
                        <td class="py-4 text-sm text-slate-700 font-medium">{{ $sales->total_quantity }}</td>
                        <td class="py-4 text-sm text-slate-700 font-medium">{{ currency_encode($sales->total_price) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($sales->review == null)
    <div class="bg-white rounded-lg mt-4 mobile:m-4 border">
        <form action="{{ route('invoice.review', $sales->invoice_number) }}" method="POST" class="flex flex-col gap-4 p-8  border-b" id="WriteReview">
            @csrf
            <div class="flex items-center gap-4">
                <h2 class="text-xl text-slate-700 font-medium flex grow">Tulis Ulasan</h2>
                <input type="text" class="w-8 h-12 opacity-0 cursor-default" name="rating" id="rating" required>
                <div class="flex flex-col items-end gap-1">
                    <div id="star-container" class="flex gap-2 justify-center text-xl cursor-pointer">
                        <span class="star text-gray-400" onclick="handleStarClick(1)">&#9733;</span>
                        <span class="star text-gray-400" onclick="handleStarClick(2)">&#9733;</span>
                        <span class="star text-gray-400" onclick="handleStarClick(3)">&#9733;</span>
                        <span class="star text-gray-400" onclick="handleStarClick(4)">&#9733;</span>
                        <span class="star text-gray-400" onclick="handleStarClick(5)">&#9733;</span>
                    </div>
                    <div id="StarLabel" class="text-xs text-slate-500"></div>
                </div>
            </div>
            <textarea name="body" id="body" rows="6" class="text-sm text-slate-500 outline-0" placeholder="Tulis pendapat atau keluhanmu mengenai produk dan pelayanan Kami"></textarea>
            <div class="flex justify-end">
                <button class="p-3 px-4 rounded-lg bg-green-500 text-sm text-white font-medium">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endif
@endsection

@section('javascript')
<script>
    const handleStarClick = (rating) => {
        const stars = document.querySelectorAll(".star");
        const starLabels = [
            "Saya Tidak Suka",
            "Kurang Baik",
            "Bisa Lebih Baik",
            "Saya Menyukainya",
            "Produk ini Fantastis!"
        ];

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add("text-yellow-400");
                star.classList.remove("text-gray-400");
            } else {
                star.classList.add("text-gray-400");
                star.classList.remove("text-yellow-400");
            }
        });

        select("#WriteReview #StarLabel").innerHTML = starLabels[rating - 1];
        select("#WriteReview #rating").value = rating;

        console.log(`Selected rating: ${rating}`);
    }
</script>
@endsection