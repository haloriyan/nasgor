@extends('layouts.user')

@section('title', "Permintaan Produk")
    
@section('content')
<div class="p-8 flex flex-col gap-4">
    <div class="flex items-center mobile:flex-row-reverse gap-4 w-full p-2 bg-white rounded-lg relative">
        <!-- Scrollable Tabs -->
        <div class="flex overflow-x-auto gap-2 pr-4 scrollbar-hide max-w-full">
            <a href="?tab=in" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == 'in' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Keluar
            </a>
            <a href="?tab=out" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == "out" ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Masuk
            </a>
        </div>
        
        <!-- Fixed Action Button -->
        <div class="ml-auto shrink-0">
            <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AddRequest')">
                <ion-icon name="add-outline" class="text-lg"></ion-icon>
                <div class="mobile:hidden">Minta Cabang Lain</div>
                <div class="desktop:hidden text-xs">Minta</div>
            </button>
        </div>
    </div>

    @if ($message != "")
        <div class="bg-green p-4 rounded-lg bg-green-500 text-white text-sm mt-6">
            {{ $message }}
        </div>
    @endif

    @include('user.stock_request.' . $tab)
</div>
@endsection

@section('ModalArea')

@include('user.stock_request.create')
@include('user.stock_request.cart')

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    let carts = [];

    const changeBranch = id => {
        new MultiSelectorAPI('#ProductSelector', [], {
            fetchUrl: `/api/product/search?branch_id=${id}&q=`,
            name: "product_ids",
            label: "Produk",
            parseResponse: (data) => data.products // if the response is { categories: [...] }
        });

        select("#QtyArea").classList.remove('hidden')
    }
    const chooseItem = (btn, item) => {
        item = atob(item);
        item = JSON.parse(item);
        
        let tr = select(`tr[itemid='${item.id}']`);
        let itemIndex = carts.findIndex(data => data.id == item.id);

        if (itemIndex < 0) {
            tr.classList.add('hidden');
            carts.push(item);
        } else {
            carts.splice(itemIndex, 1);
            tr.classList.remove('hidden');
        }

        RenderCart()
    }
    const RenderCart = () => {
        select("#CartItemArea").innerHTML = "";
        let itemIDs = [];

        carts.forEach(cart => {
            itemIDs.push(cart.id);
            let item = document.createElement('div');
            item.classList.add('flex', 'items-center', 'gap-4');
            item.innerHTML = `<div class="flex flex-col gap-1 grow">
                <div class="text-slate-700 text-sm font-medium">${cart.product.name}</div>
                <div class="flex items-center gap-2">
                    <ion-icon name="storefront-outline"></ion-icon>
                    <div class="text-xs text-slate-500">${cart.seeker_branch.name}</div>
                </div>
            </div>
            <div class="text-xs text-slate-500">Diminta : ${cart.quantity}</div>
            <div class="text-sm text-red-500 font-medium cursor-pointer" onclick="chooseItem(this, '${btoa(JSON.stringify(cart))}')">Batal</div>`;
            select("#CartItemArea").appendChild(item);
        });

        select("form#Cart #item_ids").value = itemIDs.join(',');
        console.log(itemIDs);
        

        if (itemIDs.length > 0) {
            selectAll(".AccBtn").forEach(btn => btn.classList.remove('hidden'));
            select("form#Cart").classList.remove('hidden');
        } else {
            selectAll(".AccBtn").forEach(btn => btn.classList.add('hidden'));
            select("form#Cart").classList.add('hidden');
        }
    }
    
</script>
@endsection