@extends('layouts.user')

@section('title', "Supplier")
    
@section('content')
<div class="p-8 flex flex-col gap-4">
    <div class="bg-white rounded-lg shadow shadow-slate-200 p-2 flex items-center gap-4">
        <form class="flex items-center gap-2 grow">
            <button class="flex items-center px-4">
                <ion-icon name="search-outline"></ion-icon>
            </button>
            <input type="text" name="q" class="w-full h-12 px-2 outline-0 text-sm text-slate-600" placeholder="Cari supplier">
        </form>
        <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2" onclick="toggleHidden('#AddSupplier')">
            <ion-icon name="add-outline" class="mobile:text-xl"></ion-icon>
            <div class="mobile:hidden">Tambah Supplier</div>
        </button>
    </div>
</div>

@if ($message != "")
    <div class="bg-green-500 p-2 rounded-lg text-white text-sm m-8 mt-0">
        {{ $message }}
    </div>
@endif

<div class="grid grid-cols-3 mobile:grid-cols-1 gap-8 p-8 mobile:pt-0">
    @foreach ($suppliers as $supplier)
        <div class="flex flex-col gap-4 bg-white rounded-lg shadow shadow-slate-200 p-8">
            <div class="flex items-start gap-4">
                @if ($supplier->photo == null)
                    <div class="h-24 w-24 rounded-lg bg-slate-200 flex items-center justify-center">
                        <ion-icon name="image-outline" class="text-xl"></ion-icon>
                    </div>
                @else
                    <img 
                        src="{{ asset('storage/supplier_photos/' . $supplier->photo) }}" 
                        alt="{{ $supplier->name }}" 
                        class="h-24 aspect-square rounded-lg object-cover"
                    >
                @endif
                <div class="flex flex-col gap-2 grow">
                    <h4 class="text-slate-700 fext-sm font-medium">{{ $supplier->name }}</h4>
                    <div class="flex items-center gap-4">
                        <button class="h-8 w-8 rounded-lg flex items-center justify-center text-white bg-green-500" onclick="EditSupplier('{{ $supplier }}')">
                            <ion-icon name="create-outline" class="text-lg"></ion-icon>
                        </button>
                        <button class="h-8 w-8 rounded-lg flex items-center justify-center text-white bg-red-500" onclick="DeleteSupplier('{{ $supplier }}')">
                            <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 text-slate-500 mt-2">
                <ion-icon name="person-outline"></ion-icon>
                <div class="text-xs">{{ $supplier->pic_name }}</div>
            </div>
            <div class="flex items-center gap-2 text-slate-500">
                <ion-icon name="mail-outline"></ion-icon>
                <div class="text-xs">{{ $supplier->email }}</div>
            </div>
            <div class="flex items-center gap-2 text-slate-500">
                <ion-icon name="call-outline"></ion-icon>
                <div class="text-xs">{{ $supplier->phone }}</div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('ModalArea')
@include('user.supplier.create')
@include('user.supplier.delete')
@endsection

@section('javascript')
<script>
    let isEditing = false;
    const EditSupplier = data => {
        isEditing = true;
        data = JSON.parse(data);
        let imagePreview = select("#AddSupplier #imagePreview");
        select("#AddSupplier #title").innerHTML = "Edit Supplier";
        select("#AddSupplier #id").value = data.id;
        select("#AddSupplier #name").value = data.name;
        select("#AddSupplier #pic_name").value = data.pic_name;
        select("#AddSupplier #email").value = data.email;
        select("#AddSupplier #phone").value = data.phone;
        select("#AddSupplier #address").value = data.address;

        if (data.photo !== null) {
            let filename = encodeURIComponent(data.photo); // encodes spaces, &, #, etc.
            let source = `/storage/supplier_photos/${filename}`;
            applyImageToDiv(imagePreview, source);
        }

        toggleHidden("#AddSupplier");
    }
    const DeleteSupplier = data => {
        data = JSON.parse(data);
        select("#DeleteSupplier #id").value = data.id;
        select("#DeleteSupplier #name").innerHTML = data.name;
        toggleHidden("#DeleteSupplier");
    }
    const Cancel = (target) => {
        toggleHidden(target);
        if (isEditing) {
            select("#AddSupplier #title").innerHTML = "Tambah Supplier";
            isEditing = false;
        }
    }
</script>
@endsection