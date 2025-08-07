@extends('layouts.user')

@section('title', "Produk")
    
@section('content')
<div class="p-8">
    <div class="flex items-center mobile:flex-row-reverse gap-4 w-full p-2 bg-white rounded-lg">
        <!-- Scrollable Tabs -->
        <div class="flex overflow-x-auto gap-2 pr-4 scrollbar-hide max-w-full">
            <a href="?tab=produk" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ in_array($tab, ['produk']) ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Produk
            </a>
            <a href="?tab=kategori" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ in_array($tab, ['kategori']) ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Kategori
            </a>
            <a href="?tab=addon" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ in_array($tab, ['addon']) ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Add-On
            </a>
        </div>

        <!-- Fixed Action Button -->
        <div class="ml-auto shrink-0">
            @if ($tab == "produk")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AddProduct')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Produk</div>
                    <div class="desktop:hidden text-xs">Produk</div>
                </button>
            @endif
            @if ($tab == "addon")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#CreateAddOn')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Add-On</div>
                    <div class="desktop:hidden text-xs">Add-On</div>
                </button>
            @endif
        </div>
    </div>

    @if ($message != "")
        <div class="bg-green-500 text-sm text-white font-medium p-4 rounded-lg mt-8">
            {{ $message }}
        </div>
    @endif

    @include('user.product.' . $tab . '.index', [
        'categories' => $categories,
        'products' => $products,
    ])
</div>
@endsection

@section('ModalArea')

@include('user.product.'.$tab.'.create')
@include('user.product.'.$tab.'.delete')
@include('user.product.addon.edit')

@if ($tab == "addon")
    @include('user.product.addon.add_product')
@endif

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelector.js') }}"></script>
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    const DeleteCategory = data => {
        data = JSON.parse(data);
        select("#DeleteCategory #id").value = data.id;
        select("#DeleteCategory #name").innerHTML = data.name;
        toggleHidden("#DeleteCategory");
    }
    const DeleteProduct = data => {
        data = JSON.parse(data);
        select("#DeleteProduct #id").value = data.id;
        select("#DeleteProduct #name").innerHTML = data.name;
        toggleHidden("#DeleteProduct");
    }
    const EditCategory = data => {
        data = JSON.parse(data);
        let imagePreview = select("#AddCategory #imagePreviewEdit");
        select("#AddCategory #id").value = data.id;
        select("#AddCategory #name").value = data.name;
        select("#AddCategory #title").innerHTML = `Edit Kategori ${data.name}`;
        select("#AddCategory #cancel").classList.remove('hidden');

        if (data.image !== null) {
            let filename = encodeURIComponent(data.image); // encodes spaces, &, #, etc.
            let source = `/storage/category_images/${filename}`;
            applyImageToDiv(imagePreview, source);
        }
    }
    const CancelEditCategory = () => {
        select("#AddCategory #id").value = "";
        select("#AddCategory #name").value = "";
        select("#AddCategory #title").innerHTML = `Tambah Kategori Baru`;
        select("#AddCategory #cancel").classList.add('hidden');

        select("#AddCategory #imagePreviewEdit").innerHTML = `<ion-icon name="image-outline" class="text-xl text-slate-700"></ion-icon>
        <input type="file" name="image" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" onchange="onChangeImage(this, '#imagePreviewEdit')" required>`;
    }
    const EditAddOn = (data) => {
        data = JSON.parse(data);
        select("#EditAddOn #id").value = data.id;
        select("#EditAddOn #name").value = data.name;
        select("#EditAddOn #price").value = data.price;
        toggleHidden("#EditAddOn");
    }
    const DeleteAddOn = (event, data) => {
        event.preventDefault();
        data = JSON.parse(data);
        const link = event.currentTarget;

        select("#DeleteAddOn form").setAttribute('action', link.href);
        select("#DeleteAddOn #name").innerHTML = data.name;

        toggleHidden("#DeleteAddOn");
    }

    const categories = @json($categories);
    if (select("#categorySelector") !== null) {
        new MultiSelector('#categorySelector', categories, {
            name: 'category_ids',
            label: 'Kategori Produk',
            placeholder: 'Ketik nama kategori...'
        });
    }
    if (select("#ProductSelector") !== null) {
        new MultiSelectorAPI('#ProductSelector', [], {
            fetchUrl: '/api/product/search?branch_id={{ $me->access->branch_id }}&q=',
            name: "product_ids",
            label: "Berlaku untuk Produk",
            parseResponse: (data) => data.products // if the response is { categories: [...] }
        });
    }
    if (select("#AddOn_ProductSelector") !== null) {
        new MultiSelectorAPI('#AddOn_ProductSelector', [], {
            fetchUrl: '/api/product/search?branch_id={{ $me->access->branch_id }}&q=',
            name: "product_ids",
            label: "Cari Produk",
            parseResponse: (data) => data.products // if the response is { categories: [...] }
        });
    }

    const AddProductToAddOn = (event, addon) => {
        event.preventDefault();
        addon = JSON.parse(addon);
        const link = event.currentTarget;
        select("#AddProductAddOn form").setAttribute("action", link.href);
        select("#AddProductAddOn #name").innerHTML = addon.name;
        toggleHidden("#AddProductAddOn");
    }
</script>
@endsection