@extends('layouts.user')

@section('title', "Detail Produk")

@php
    use Carbon\Carbon;
@endphp
    
@section('content')
<div class="p-8 flex flex-col gap-4">
    <div class="flex items-center gap-4 p-4 bg-white rounded-lg shadow shadow-slate-200">
        <a href="{{ route('product') }}" class="flex items-center">
            <ion-icon name="arrow-back-outline" class="text-lg text-slate-700"></ion-icon>
        </a>
        <div class="flex flex-col grow">
            <h2 class="text-lg text-slate-700 font-medium">{{ $product->name }}</h2>
        </div>
        <button class="bg-red-500 text-white text-sm font-medium p-3 px-5 rounded-lg flex items-center gap-4">
            <ion-icon name="trash-outline" class="text-lg"></ion-icon>
            Hapus
        </button>
    </div>

    @if ($message != "")
        <div class="bg-green-500 p-4 roubded-lg text-white text-sm rounded-lg">
            {{ $message }}
        </div>
    @endif

    <div class="flex mobile:flex-col items-start gap-8">
        <div class="flex flex-col w-4/12 gap-8 mobile:w-full">
            {{-- bg-white rounded-lg border --}}
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                    <ion-icon name="image-outline"></ion-icon>
                    <div>Gambar</div>
                </div>
                <div class="p-8">
                    <div class="flex flex-wrap items-center gap-4">
                        @foreach ($product->images as $i => $image)
                            <div class="relative group">
                                <img 
                                    src="{{ asset('storage/product_images/' . $image->filename) }}" 
                                    alt="{{ $i }}"
                                    class="w-20 h-20 rounded-lg object-cover"
                                >
                                <div class="absolute top-1 right-1">
                                    <a href="{{ route('product.detail.image.delete', [$product->id, $image->id]) }}" class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded opacity-0 group-hover:opacity-100">
                                        <ion-icon name="close-outline" class="text-lg"></ion-icon>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <form id="FormImage" action="{{ route('product.detail.image.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="w-20 h-20 bg-slate-200 rounded-lg flex items-center justify-center relative">
                            @csrf
                            <ion-icon name="add-outline" class="text-lg text-slate-600"></ion-icon>
                            <input type="file" name="image" class="absolute top-0 left-0 right-0 bottom-0 cursor-pointer opacity-0" onchange="triggerImage()">
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg border">
                <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                    <ion-icon name="pricetags-outline"></ion-icon>
                    <div>Kategori</div>
                </div>
                <div class="p-8 flex flex-col gap-4">
                    <div class="flex flex-wrap items-center gap-4">
                        @foreach ($product->categories as $category)
                            <div class="h-8 px-4 rounded-full bg-primary text-white text-sm flex items-center justify-center gap-3">
                                {{ $category->name }}
                                <a href="{{ route('product.detail.category.toggle', [$product->id, $category->id]) }}" class="w-4 h-4 rounded-full bg-white text-primary flex items-center justify-center">
                                    <ion-icon name="close-outline"></ion-icon>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('product.detail.category.toggle', $product->id) }}" method="POST" id="StoreCategory" class="flex justify-end">
                        @csrf
                        <select name="category_id" class="border h-10 px-4 text-sm text-slate-700 rounded-lg outline-0" onchange="submitStoreCategory()" required>
                            <option value="">Tambah</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <form action="{{ route('product.detail.updateInfo', $product->id) }}" method="POST" class="flex flex-col grow mobile:w-full bg-white rounded-lg border">
            @csrf
            <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                <ion-icon name="create-outline"></ion-icon>
                <div class="flex grow">Informasi Produk</div>
                <button class="bg-green-500 text-white text-sm p-2 px-4 rounded-lg font-medium">
                    Simpan
                </button>
            </div>
            
            <div class="p-8 flex flex-col gap-4">
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama</label>
                    <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" value="{{ $product->name }}" required />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="w-full mt-6 outline-none bg-transparent text-sm text-slate-700">{{ $product->description }}</textarea>
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Harga Pokok Produksi</label>
                    <input type="text" name="price" id="price" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" value="{{ $product->price }}" required />
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-3 mobile:grid-cols-1 mt-4 gap-8">
        <div class="flex flex-col bg-white rounded-lg border">
            <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                <ion-icon name="cash-outline"></ion-icon>
                <div class="flex grow">Harga</div>
                <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#AddPrice')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                </button>
            </div>
            <div class="p-8 flex flex-col gap-2">
                @foreach ($product->prices as $i => $item)
                    <div class="flex items-center gap-2">
                        <div class="text-sm text-slate-700">{{ $item->label }}</div>
                        <div class="text-xs text-primary flex grow">{{ currency_encode($item->value) }}</div>
                        <a href="{{ route('product.detail.removePrice', [$product->id, $item->id]) }}" class="p-2 px-3 rounded-lg bg-red-200 text-red-500 flex items-center" onclick="RemovePrice(event, '{{ $item }}')">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col bg-white rounded-lg border">
            <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                <ion-icon name="list-outline"></ion-icon>
                <div class="flex grow">Bahan & Resep</div>
                <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#AddIngredient')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                </button>
            </div>
            <div class="p-8">
                <div class="text-xs text-slate-500">Produk-produk ini juga akan dikurangi setiap {{ $product->name }} terjual.</div>
                <div class="flex flex-col gap-2 mt-4">
                    @foreach ($product->ingredients as $ingredient)
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-slate-700">{{ $ingredient->ingredient->name }}</div>
                            <div class="flex grow bg-slate-300 h-[1px]"></div>
                            <div class="text-xs text-slate-500">{{ $ingredient->quantity }}x</div>
                            <a href="{{ route('product.detail.ingredient.delete', [$product->id, $ingredient->id]) }}" class="p-2 px-3 rounded-lg bg-red-200 text-red-500 flex items-center" onclick="RemoveIngredient(event, '{{ $ingredient }}')">
                                <ion-icon name="close-outline"></ion-icon>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex flex-col bg-white rounded-lg border">
            <div class="flex items-center gap-4 border-b text-slate-600 h-16 px-8">
                <ion-icon name="add-circle-outline"></ion-icon>
                <div class="flex grow">Add Ons</div>
                <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#AddAddOn')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                </button>
            </div>
            <div class="p-8 flex flex-col gap-2">
                @foreach ($product->addons as $addon)
                    <div class="flex items-center gap-2">
                        <div class="text-sm text-slate-700">{{ $addon->addon->name }}</div>
                        <div class="text-xs text-slate-500 flex grow">{{ currency_encode($addon->addon->price) }}</div>
                        <a href="{{ route('product.detail.addon.delete', [$product->id, $addon->id]) }}" class="p-2 px-3 rounded-lg bg-red-200 text-red-500 flex items-center" onclick="RemoveAddOn(event, '{{ $addon }}')">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')

@include('user.product.add_price')
@include('user.product.remove_price')
@include('user.product.add_ingredient')
@include('user.product.remove_ingredient')
@include('user.product.add_addon')
@include('user.product.remove_addon')

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelector.js') }}"></script>
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    const triggerImage = () => {
        select("#FormImage").submit();
    }
    const RemovePrice = (event, data) => {
        event.preventDefault();
        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#RemovePrice form").setAttribute('action', link.href);
        select("#RemovePrice #label").innerHTML = data.label;

        toggleHidden("#RemovePrice");
    }
    const RemoveAddOn = (event, data) => {
        event.preventDefault();
        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#RemoveAddOn form").setAttribute('action', link.href);
        select("#RemoveAddOn #name").innerHTML = data.addon.name;

        toggleHidden("#RemoveAddOn");
    }
    const RemoveIngredient = (event, data) => {
        event.preventDefault();
        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#RemoveIngredient form").setAttribute('action', link.href);
        select("#RemoveIngredient #name").innerHTML = data.ingredient.name;

        toggleHidden("#RemoveIngredient");
    }
    const submitStoreCategory = () => {
        select("#StoreCategory").submit();
    }

    const availableAddOns = @json($addOns);
    if (select("#AddOnsSelector") !== null) {
        new MultiSelector('#AddOnsSelector', availableAddOns, {
            name: 'addon_ids',
            label: 'Add Ons',
            placeholder: 'Ketik nama add on...'
        });
    }
    new MultiSelectorAPI('#ProductSelector', [], {
        fetchUrl: '/api/product/search?branch_id={{ $product->branch_id }}&q=',
        name: "ingredient_id",
        label: "Produk",
        // single: true,
        parseResponse: (data) => data.products // if the response is { categories: [...] }
    });
</script>
@endsection