@extends('layouts.user')

@section('title', "Detail Purchasing")

@php
    use Carbon\Carbon;
    $supplier = $purchasing->supplier;
    $canEdit = $purchasing->status == "DRAFT";
@endphp
    
@section('content')
<div class="p-8 mobile:p-6">
    <div class="bg-white rounded-lg flex mobile:flex-wrap items-center gap-8 shadow shadow-slate-200 p-8">
        <a href="{{ route('purchasing') }}" class="flex items-center">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <div class="flex flex-col gap-1 grow">
            <h2 class="text-lg text-slate-700 font-medium">{{ $purchasing->label }}</h2>
            <div class="text-xs text-slate-500">Pembelian oleh {{ $purchasing->creator->name }}</div>
        </div>
        @if ($purchasing->status == "DRAFT")
            <form action="{{ route('purchasing.receive', $purchasing->id) }}" method="POST" class="flex flex-col gap-2" id="proceed">
                @csrf
                <input type="hidden" name="store_movement" id="store_movement" value="0">
                <button class="bg-green-500 text-white text-sm p-3 px-6 rounded-lg font-medium">
                    Proses
                </button>
                <div class="flex items-center gap-4">
                    <div class="text-xs text-slate-500 flex grow">Tambah ke Stok Masuk?</div>
                    <div class="p-1 rounded-full bg-slate-200 cursor-pointer" onclick="toggleMovement(this, 'form#proceed')" id="toggleMovementBtn">
                        <div class="h-6 w-6 bg-white rounded-full me-6" id="SwitchDot"></div>
                    </div>
                </div>
            </form>
        @endif
        @if ($purchasing->status == "RECEIVED")
            <div class="flex flex-col gap-1 mobile:basis-96 mobile:grow">
                <div class="text-xs text-slate-500">Diterima oleh {{ $purchasing->receiver->name }}</div>
                @if ($purchasing->inventory_id != null)
                    <a href="{{ route('inventory.detail', $purchasing->inventory_id) }}" class="bg-primary text-white text-sm p-3 px-6 rounded-lg font-medium">
                        Lihat di Stok Masuk
                    </a>
                @endif
            </div>
        @endif
    </div>
    @if ($message != "")
        <div class="bg-green-500 p-4 rounded-lg text-sm text-white mt-8">
            {{ $message }}
        </div>
    @endif
    <div class="grid grid-cols-3 mobile:grid-cols-1 gap-8 mt-8 items-start">
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-primary text-white font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Supplier</div>
                @if ($canEdit)
                    <button class="bg-white text-xs rounded-lg text-primary p-2 px-3 flex items-center gap-2" onclick="toggleHidden('#EditSupplier')">
                        <ion-icon name="create-outline" class="text-lg"></ion-icon>
                        Ubah
                    </button>
                @endif
            </div>
            <div class="p-8">
                <div class="flex justify-center">
                    <img 
                        src="{{ asset('storage/supplier_photos/' . $supplier->photo) }}" 
                        alt="{{ $supplier->name }}"
                        class="w-32 aspect-square rounded-xl object-cover"
                    >
                </div>
                <div class="text-lg text-slate-700 font-medium mt-6 mb-4">{{ $supplier->name }}</div>
                <div class="flex items-center gap-4 mt-2">
                    <ion-icon name="person-outline" class="text-slate-500"></ion-icon>
                    <div class="text-xs text-slate-500">PIC</div>
                    <div class="flex grow justify-end text-sm text-slate-700">{{ $supplier->pic_name }}</div>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <ion-icon name="call-outline" class="text-slate-500"></ion-icon>
                    <div class="text-xs text-slate-500">No. Telepon</div>
                    <div class="flex grow justify-end text-sm text-slate-700">{{ $supplier->phone }}</div>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <ion-icon name="mail-outline" class="text-slate-500"></ion-icon>
                    <div class="text-xs text-slate-500">Email</div>
                    <div class="flex grow justify-end text-sm text-slate-700">{{ $supplier->email }}</div>
                </div>
                <div class="text-xs text-slate-500 mt-4">Alamat</div>
                <div class="text-sm text-slate-700 mt-2">{{ $supplier->address }}</div>
            </div>
        </div>
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-primary text-white font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Catatan</div>
                @if ($canEdit == "DRAFT")
                    <button class="bg-white text-xs rounded-lg text-primary p-2 px-3 flex items-center gap-2" onclick="EditNotes({{ $purchasing->id }}, '{{ $purchasing->notes }}')">
                        <ion-icon name="create-outline" class="text-lg"></ion-icon>
                        Ubah
                    </button>
                @endif
            </div>
            <div class="p-8">
                <div class="text-sm text-slate-600">{{ $purchasing->notes == null ? 'Tidak ada catatan' : $purchasing->notes }}</div>
            </div>
        </div>
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-primary text-white font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Log</div>
            </div>
            <div class="p-8 flex flex-col gap-2">
                <div class="text-xs text-slate-500 mb-1">Dibuat</div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="person-outline"></ion-icon>
                    <div class="text-xs">{{ $purchasing->creator->name }}</div>
                </div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="time-outline"></ion-icon>
                    <div class="text-xs">{{ Carbon::parse($purchasing->created_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</div>
                </div>
                <div class="text-xs text-slate-500 mt-4">Diperbarui</div>
                <div class="flex items-center gap-2 text-slate-700 text-sm">
                    <ion-icon name="time-outline"></ion-icon>
                    <div class="text-xs">{{ Carbon::parse($purchasing->updated_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</div>
                </div>

                @if ($purchasing->received_at != null)
                    <div class="text-xs text-slate-500 mt-4">Penerima</div>
                    <div class="flex items-center gap-2 text-slate-700 text-sm">
                        <ion-icon name="person-outline"></ion-icon>
                        <div class="text-xs">{{ $purchasing->receiver->name }}</div>
                    </div>
                    <div class="flex items-center gap-2 text-slate-700 text-sm">
                        <ion-icon name="time-outline"></ion-icon>
                        <div class="text-xs">{{ Carbon::parse($purchasing->received_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="flex flex-col rounded-lg bg-white mt-8">
        <div class="rounded-t-lg bg-primary text-white font-medium p-4 px-8 flex items-center gap-4">
            <div class="flex grow">Produk</div>
            @if ($canEdit)
                <button class="bg-white text-xs rounded-lg text-primary p-2 px-3 flex items-center gap-2" onclick="toggleHidden('#AddProduct')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    Tambah
                </button>
            @endif
        </div>
        <div class="min-w-full overflow-hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                        <th scope="col" class="px-6 py-3 text-left">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left">Total</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($purchasing->items as $i => $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700 w-8">
                                {{ $i + 1}}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-4">
                                <div>{{ $item->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ currency_encode($item->price) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-2">
                                <div>{{ $item->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ currency_encode($item->total_price) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-4">
                                @if ($canEdit)
                                    <div class="bg-green-500 text-white flex items-center p-2 px-3 rounded-lg cursor-pointer" onclick="EditQuantity('{{ $item }}')">
                                        <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                    </div>
                                    <a href="{{ route('purchasing.detail.removeProduct', [$purchasing->id, $item->id]) }}" class="bg-red-500 text-white flex items-center p-2 px-3 rounded-lg" onclick="RemoveProduct(event, '{{ $item }}')">
                                        <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="w-8"></th>
                        <th class="px-6 py-4 text-left text-sm text-slate-700" colspan="2">
                            Subtotal
                        </th>
                        <th class="px-6 py-4 text-left text-sm text-slate-700">{{ $purchasing->total_quantity }}</th>
                        <th class="px-6 py-4 text-left text-sm text-slate-700">{{ currency_encode($purchasing->total_price) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
@include('user.purchasing.edit_notes')
@include('user.purchasing.edit_supplier')
@include('user.purchasing.edit_quantity')
@include('user.purchasing.add_product')
@include('user.purchasing.remove_product')
@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    const EditNotes = (id, notes) => {
        select("#EditNotes #id").value = id;
        select("#EditNotes #notes").value = notes;
        toggleHidden('#EditNotes');
    }
    const EditQuantity = data => {
        data = JSON.parse(data);
        select("#EditQuantity #id").value = data.id;
        select("#EditQuantity #quantity").value = data.quantity;
        select("#EditQuantity #price").value = data.price;
        toggleHidden("#EditQuantity");
    }
    const RemoveProduct = (event, data) => {
        event.preventDefault();

        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#RemoveProduct form").setAttribute('action', link.href);
        select("#RemoveProduct #name").innerHTML = data.product.name;
        toggleHidden("#RemoveProduct");
    }
    const toggleMovement = (btn, prefix) => {
        let btnClasses = btn.classList;
        let dotClasses = select(`${prefix} #SwitchDot`).classList;
        let newValue = 0;

        if (btnClasses.contains('bg-green-500')) {
            newValue = 0;
            btnClasses.remove('bg-green-500');
            btnClasses.add('bg-slate-200');
            dotClasses.remove('ms-6');
            dotClasses.add('me-6');
        } else {
            newValue = 1;
            btnClasses.remove('bg-slate-200');
            btnClasses.add('bg-green-500');
            dotClasses.remove('me-6');
            dotClasses.add('ms-6');
        }

        select(`${prefix} #store_movement`).value = newValue.toString();
    }

    new MultiSelectorAPI('#ProductSelector', [], {
        fetchUrl: '/api/product/search?branch_id={{ $purchasing->branch_id }}&q=',
        name: "product_ids",
        label: "Produk",
        parseResponse: (data) => data.products // if the response is { categories: [...] }
    });

    select("#toggleMovementBtn").click();
</script>
@endsection