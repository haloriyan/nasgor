@php
    use Carbon\Carbon;
@endphp
<div class="grid grid-cols-3 gap-8 my-4">
    <div class="bg-white rounded-lg border">
        <div class="p-8 flex items-center gap-4">
            <div class="text-2xl text-slate-700 flex grow">{{ $reviewProportion }}%</div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-500 text-white">
                <ion-icon name="people-outline" class="text-xl"></ion-icon>
            </div>
        </div>
        <div class="border-t p-4 px-8">
            <div class="text-xs text-slate-400 font-medium">Orang Memberi Ulasan</div>
        </div>
    </div>
    <div class="bg-white rounded-lg border">
        <div class="p-8 flex items-center gap-4">
            <div class="text-2xl text-slate-700 flex grow">{{ $totalReviews }}</div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-500 text-white">
                <ion-icon name="create-outline" class="text-xl"></ion-icon>
            </div>
        </div>
        <div class="border-t p-4 px-8">
            <div class="text-xs text-slate-400 font-medium">Ulasan Diberikan</div>
        </div>
    </div>
    <div class="bg-white rounded-lg border">
        <div class="p-8 flex items-center gap-4">
            <div class="text-2xl text-slate-700 ">{{ $averageRating }}</div>
            <div class="text-xs text-slate-500 flex grow">dari 5</div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-yellow-500 text-white">
                <ion-icon name="star" class="text-xl"></ion-icon>
            </div>
        </div>
        <div class="border-t p-4 px-8">
            <div class="text-xs text-slate-400 font-medium">Rating Rata-rata</div>
        </div>
    </div>
</div>
<div class="flex mobile:flex-col gap-8 mobile:gap-4">
    <div class="flex flex-col w-4/12 bg-white rounded-lg shadow shadow-slate-200 p-8">
        <div class="text-sm text-slate-500 font-medium">Filter</div>
        <div class="flex flex-col gap-4 mt-4">
            <a href="?tab=review" class="flex items-center gap-4 border rounded-lg p-4 {{ $request->filter_rating == "" ? 'text-primary border-primary' : 'text-slate-600' }}">
                <div class="text-xs flex grow justify-center">Tampilkan semua</div>
            </a>
            @for ($i = 5; $i >= 1; $i--)
                <a href="?tab=review&filter_rating={{ $i }}" class="flex items-center gap-4 border rounded-lg p-4 {{ $request->filter_rating == $i ? 'border-primary text-primary' : 'text-slate-600' }}">
                    <div class="text-xs flex grow ">Rating {{ $i }} ke bawah</div>
                    <div class="flex items-center gap-2">
                        @for ($j = 0; $j < 5; $j++)
                            <ion-icon name="star" class="{{ $i <= $j ? 'text-slate-500' : 'text-yellow-500' }}"></ion-icon>
                        @endfor
                    </div>
                </a>
            @endfor
        </div>
    </div>
    <div class="flex flex-col grow gap-6">
        @foreach ($reviews as $review)
            <div class="bg-white rounded-lg shadow shadow-slate-200 p-8 flex flex-col gap-4">
                <div class="flex items-start gap-4">
                    <div class="text-sm text-slate-500 font-medium flex grow">{{ $review->customer->name }}</div>
                    <div class="flex items-center gap-2">
                        @for ($i = 0; $i < 5; $i++)
                            <ion-icon name="star" class="{{ $review->rating <= $i ? 'text-slate-500' : 'text-yellow-500' }}"></ion-icon>
                        @endfor
                    </div>
                </div>
                <div class="text-xs text-slate-600">{{ $review->body }}</div>
                <div class="text-xs text-slate-400 flex items-center justify-end gap-2">
                    <ion-icon name="time-outline"></ion-icon>
                    {{ Carbon::parse($review->created_at)->isoFormat('DD MMMM YYYY, HH:mm') }}
                </div>
                <div class="border-t mt-4 pt-4">
                    <div class="flex items-center gap-4">
                        <div class="text-xs text-slate-400 font-medium flex grow">Produk yang Dibeli</div>
                        <div class="flex items-center gap-2">
                            @foreach ($review->order->items as $item)
                                <div class="text-xs text-primary border rounded-full p-1 px-3">
                                    {{ $item->product->name }} x {{ $item->quantity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>