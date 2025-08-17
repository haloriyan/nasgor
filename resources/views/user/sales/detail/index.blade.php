@extends('layouts.user')

@section('title', "Detail Penjualan")

@php
    use Carbon\Carbon;
@endphp
    
@section('content')
<div class="flex flex-col gap-8 p-8">
    <div class="flex items-center gap-4 bg-white rounded-lg shadow shadow-slate-200 p-8">
        <a href="{{ route('sales') }}" class="flex items-center">
            <ion-icon name="arrow-back-outline" class="text-xl text-slate-600"></ion-icon>
        </a>
        <div class="flex flex-col gap-1 grow">
            <h3 class="text-lg text-slate-700 font-medium">{{ $sales->invoice_number }}</h3>
        </div>
        @if ($sales->status == "DRAFT")
            <a href="{{ route('sales.proceed', $sales->id) }}" class="bg-green-500 text-white text-sm p-3 px-6 rounded-lg font-medium">
                Proses
            </a>
        @else
            <div class="flex items-center gap-2">
                <div class="text-xs p-2 px-3 rounded-full {{ $sales->payment_status == "UNPAID" ? 'bg-slate-200 text-slate-500' : 'bg-white text-slate-700'}}">Belum Bayar</div>
                <a href="{{ route('sales.detail.togglePaymentStatus', $sales->id) }}" class="p-1 rounded-full {{ $sales->payment_status == "PAID" ? 'bg-green-500' : 'bg-slate-200' }}">
                    <div class="h-6 w-6 bg-white rounded-full {{ $sales->payment_status == "PAID" ? 'ms-6' : 'me-6' }}" id="SwitchDot"></div>
                </a>
                <div class="text-xs p-2 px-3 rounded-full {{ $sales->payment_status == "PAID" ? 'bg-green-500 text-white' : 'text-slate-700' }}">Lunas</div>
            </div>
        @endif
    </div>

    @if ($message != "")
        <div class="bg-green-500 text-white text-sm rounded-lg p-4">
            {{ $message }}
        </div>
    @endif

    <div class="grid grid-cols-3 mobile:grid-cols-1 gap-8">
        @if ($sales->customer != null)
            <div class="bg-white rounded-lg border">
                <div class="h-20 px-8 flex items-center gap-4 border-b">
                    <ion-icon name="person-outline" class="text-xl"></ion-icon>
                    <div class="flex grow">Pelanggan</div>
                    @if ($sales->status == "DRAFT")
                        <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#EditCustomer')">
                            Ganti
                        </button>
                    @endif
                </div>
                <div class="p-8">
                    <div class="text-slate-700 font-medium flex grow mt-1">{{ @$sales->customer->name }}</div>
                    <div class="flex items-center gap-2 mt-2">
                        @foreach (@$sales->customer->types as $type)
                            <div class="p-1 px-3 rounded-lg text-xs text-white font-medium" style="background-color: {{ $type->color }}25;color: {{ $type->color }}">
                                {{ $type->name }}
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-4 text-slate-500 mt-6">
                        <ion-icon name="call-outline" class="text-lg"></ion-icon>
                        <div class="text-sm">{{ @$sales->customer->phone }}</div>
                    </div>
                    <div class="flex items-center gap-4 text-slate-500 mt-2">
                        <ion-icon name="mail-outline" class="text-lg"></ion-icon>
                        <div class="text-sm">{{ @$sales->customer->email }}</div>
                    </div>

                    @if ($sales->review != null)
                        <div class="border-t pt-4 mt-4">
                            <div class="flex items-center gap-2">
                                <div class="text-xs text-slate-400">Penilaian :</div>
                                <div class="flex items-center justify-end grow gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <ion-icon name="star" class="{{ $sales->review->rating < $i ? 'text-slate-400' : 'text-yellow-500' }}"></ion-icon>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-sm text-slate-600 mt-4 border rounded-lg p-4">{{ $sales->review->body }}</div>
                        </div>
                    @endif

                    @if ($sales->review == null && $sales->payment_status == "PAID" && @$sales->customer->phone != null)
                        <div class="border-t pt-4 mt-4 flex justify-end">
                            <a href="{{ $waLink }}" class="bg-green-500 p-2 px-4 rounded-full text-white text-sm font-medium" target="_blank">
                                Kirim Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="bg-white rounded-lg border">
            <div class="h-20 px-8 flex items-center gap-4 border-b">
                <ion-icon name="create-outline" class="text-xl"></ion-icon>
                <div class="flex grow">Catatan</div>
                @if ($sales->status == "DRAFT")
                    <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#EditNotes')">
                        Edit
                    </button>
                @endif
            </div>
            <div class="p-8">
                {{ $sales->notes }}
            </div>
        </div>
        <div class="bg-white rounded-lg border">
            <div class="h-20 px-8 flex items-center gap-4 border-b">
                <ion-icon name="sync-outline" class="text-xl"></ion-icon>
                <div class="flex grow">Log</div>
            </div>
            <div class="p-8 flex flex-col gap-2">
                <div class="text-xs text-slate-500 mb-1">Dibuat</div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="person-outline"></ion-icon>
                    <div class="text-xs">{{ $sales->user->name }}</div>
                </div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="time-outline"></ion-icon>
                    <div class="text-xs">{{ Carbon::parse($sales->created_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</div>
                </div>
                <div class="text-xs text-slate-500 mt-4">Diperbarui</div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="time-outline"></ion-icon>
                    <div class="text-xs">{{ Carbon::parse($sales->updated_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col bg-white rounded-lg border">
        <div class="h-20 px-8 flex items-center gap-4 border-b">
            <ion-icon name="cube-outline" class="text-xl"></ion-icon>
            <div class="flex grow">Produk</div>
            @if ($sales->status == "DRAFT")
                <button class="p-2 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center" onclick="toggleHidden('#AddProduct')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                </button>
            @endif
        </div>
        <div class="min-w-full overflow-hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left">Qty</th>
                        <th scope="col" class="px-6 py-3 text-left">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left">Total</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($sales->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="flex items-start gap-4">
                                    @if ($item->product->images->count() > 0)
                                        <img 
                                            src="{{ asset('storage/product_images/' . @$item->product->images[0]->filename) }}" 
                                            alt="{{ $item->id }}"
                                            class="w-16 h-16 rounded-lg object-cover"
                                        >
                                    @else 
                                        <div class="flex items-center justify-center rounded-lg w-16 h-16 bg-slate-100">
                                            <ion-icon name="image-outline" class="text-lg"></ion-icon>
                                        </div>
                                    @endif
                                    <div class="flex flex-col gap-2">
                                        <div class="text-sm text-slate-600 font-medium">{{ @$item->product->name }}</div>
                                        <div class="flex flex-col gap-1">
                                            @foreach ($item->addons as $add)
                                                <div class="text-xs text-slate-500">+ {{ $add->quantity }} {{ $add->addon->name }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="text-sm text-slate-600 font-medium">
                                    {{ currency_encode($item->total_price) }}
                                </div>
                                @if ($item->additional_price > 0)
                                    <div class="text-xs text-slate-500 mt-2">
                                        + {{ currency_encode($item->additional_price) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="text-sm text-slate-600 font-medium">
                                    {{ currency_encode($item->grand_total) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="flex">
                                    @if ($sales->status == "DRAFT")
                                        <a href="{{ route('sales.detail.product.delete', [$sales->id, $item->id]) }}" class="flex items-center p-2 px-3 rounded-lg bg-red-500 text-white" onclick="RemoveProduct(event, '{{ $item }}')">
                                            <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="px-6 py-4 text-sm text-slate-700 text-left">
                            Total
                        </th>
                        <th class="px-6 py-4 text-sm text-slate-700 text-left" colspan="2">
                            {{ $sales->total_quantity }}
                        </th>
                        <th class="px-6 py-4 text-sm text-slate-700 text-left">
                            {{ currency_encode($sales->total_price) }}
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
    
@if ($sales->status == "DRAFT")
    @include('user.sales.detail.add_product')
    @include('user.sales.detail.remove_product')
    @include('user.sales.detail.edit_notes')
    @include('user.sales.detail.edit_customer')
@endif

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    let product = null;
    const RenderProductSelector = () => {
        select("#ProductSelector").innerHTML = "";
        new MultiSelectorAPI('#ProductSelector', [], {
            fetchUrl: '/api/product/search?branch_id={{ $sales->branch_id }}&q=',
            name: "product_ids",
            label: "Produk",
            single: true,
            parseResponse: (data) => data.products,
            onChoose: datas => {
                chooseProduct(datas[0]);
            }
        });
    }

    const chooseProduct = (prod) => {
        
        select("#ProductSelectorWrapper").classList.add('hidden');
        select("#ProductDetailWrapper").classList.remove('hidden');
        select("#AddProduct #product_id").value = prod.id;
        if (prod.images.length > 0) {
            select("#AddProduct #ProductImage").setAttribute('src', `/storage/product_images/${prod.images[0].filename}`);
        }
        select("#AddProduct #ProductName").innerHTML = prod.name;

        // Render Add Ons
        prod.addons.forEach((item, i) => {
            let el = document.createElement("div");
            el.classList.add("flex", "items-center", "gap-4");
            el.innerHTML = `<div class="text-sm text-slate-700 flex grow">${item.addon.name}</div>
            <input type="number" value="0" name="addon_${i}" id="addon_${i}" addon-data="${btoa(JSON.stringify(item))}" oninput="updateAddOnQty(this)" class="h-10 outline-0 border text-sm w-20 text-center" />`;
            select("#AddProduct #AddOnArea").appendChild(el);
        });

        // Render Prices
        prod.prices.forEach((price, p) => {
            let option = document.createElement('option');
            option.value = price.id;
            option.innerHTML = `${price.label} - ${Currency(price.value).encode()}`;
            select("#AddProduct #product_price_id").appendChild(option);
        })
        
    }
    
    let addons = [];
    const updateAddOnQty = (target) => {
        let selectedAddOn = JSON.parse(atob(target.getAttribute('addon-data')));
        let quantity = target.value;

        if (addons.length === 0) {
            addons.push({
                id: selectedAddOn.addon.id,
                quantity: quantity,
            });
        } else {
            
            addons.forEach((addon, a) => {
                if (addon.id === selectedAddOn.addon.id) {
                    addons[a]['quantity'] = quantity;
                } else {
                    addons.push({
                        id: selectedAddOn.addon.id,
                        quantity: quantity,
                    });
                }
            });
        }

        select("#AddProduct #addons").value = JSON.stringify(addons);
        
    }
    const CancelChooseProduct = () => {
        RenderProductSelector();
        select("#AddProduct #product_id").value = "";
        select("#ProductSelectorWrapper").classList.remove('hidden');
        select("#ProductDetailWrapper").classList.add('hidden');
    }

    RenderProductSelector();

    const RemoveProduct = (event, data) => {
        event.preventDefault();
        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#RemoveProduct form").setAttribute('action', link.href);
        select("#RemoveProduct #name").innerHTML = data.product.name;

        toggleHidden("#RemoveProduct");
    }

    const EditNotes = (id, notes) => {
        select("#EditNotes #id").value = id;
        select("#EditNotes #notes").value = notes;
        toggleHidden('#EditNotes');
    }

    if (select('#CustomerSelector') !== null) {
        new MultiSelectorAPI('#CustomerSelector', [], {
            fetchUrl: '/api/customer/search?branch_id={{ $sales->branch_id }}&q=',
            name: "customer_ids",
            label: "Pelanggan",
            single: true,
            parseResponse: (data) => data.customers // if the response is { categories: [...] }
        });
    }
</script>
@endsection