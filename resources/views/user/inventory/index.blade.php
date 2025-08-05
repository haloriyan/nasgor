@extends('layouts.user')

@section('title', "Inventori")
    
@section('content')
<div class="p-8 flex flex-col gap-4">
    <div class="flex items-center mobile:flex-row-reverse gap-4 w-full p-2 bg-white rounded-lg relative">
        <!-- Scrollable Tabs -->
        <div class="flex overflow-x-auto gap-2 pr-4 scrollbar-hide max-w-full">
            <a href="?tab=inbound" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == 'inbound' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Stok Masuk
            </a>
            <a href="?tab=outbound" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == "outbound" ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Stok Keluar
            </a>
            <a href="?tab=opname" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == 'opname' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Stok Opname
            </a>
        </div>

        <div class="absolute top-2 right-4 bg-white border flex flex-col rounded hidden ContextMenu" id="CreateInboundContext">
            <div class="text-sm text-slate-600 p-3 px-4 cursor-pointer rounded hover:text-primary hover:bg-slate-100" onclick="Create('purchasing')">
                Terima dari Pembelian
            </div>
            <div class="text-sm text-slate-600 p-3 px-4 cursor-pointer rounded hover:text-primary hover:bg-slate-100" onclick="Create()">
                Tambah Stok Masuk
            </div>
        </div>

        <div class="absolute top-2 right-4 bg-white border flex flex-col rounded hidden ContextMenu" id="CreateOutboundContext">
            <div class="text-sm text-slate-600 p-3 px-4 cursor-pointer rounded hover:text-primary hover:bg-slate-100" onclick="CreateOutbound('transfer')">
                Transfer ke Cabang
            </div>
            <div class="text-sm text-slate-600 p-3 px-4 cursor-pointer rounded hover:text-primary hover:bg-slate-100" onclick="CreateOutbound()">
                Tambah Stok Keluar
            </div>
        </div>
        
        <!-- Fixed Action Button -->
        <div class="ml-auto shrink-0">
            @if ($tab == "inbound")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleContextMenu(event, '#CreateInboundContext')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Stok Masuk</div>
                    <div class="desktop:hidden text-xs">Stok Masuk</div>
                </button>
            @endif
            @if ($tab == "outbound")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleContextMenu(event, '#CreateOutboundContext')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Stok Keluar</div>
                    <div class="desktop:hidden text-xs">Stok Keluar</div>
                </button>
            @endif
            @if ($tab == "opname")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#CreateOpname')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Stok Opname</div>
                    <div class="desktop:hidden text-xs">Stok Opname</div>
                </button>
            @endif
        </div>
    </div>

    @include('user.inventory.' . $tab . '.index', [
        'inventories' => $inventories,
    ])
</div>
@endsection

@section('ModalArea')
    
@include('user.inventory.'.$tab.'.create')
@include('user.inventory.'.$tab.'.create')
@include('user.inventory.'.$tab.'.create')

@endsection

@section('javascript')
<script>
    const Create = (origin = null) => {
        if (origin === "purchasing") {
            select("#Create #title").innerHTML = "Terima dari Pembelian";
            select("#Create #supplier_id").removeAttribute('required');
            select("#Create #supplier_id").selectedIndex = 0;
            select("#Create #SupplierInput").classList.add("hidden");

            select("#Create #purchasing_id").setAttribute('required', 'required');
            select("#Create #PurchasingInput").classList.remove('hidden');
        } else {
            select("#Create #title").innerHTML = "Tambah Stok Masuk";
            select("#Create #supplier_id").setAttribute('required', 'required');
            select("#Create #SupplierInput").classList.remove("hidden");

            select("#Create #purchasing_id").removeAttribute('required');
            select("#Create #PurchasingInput").classList.add('hidden');
            select("#Create #purchasing_id").selectedIndex = 0;
        }

        toggleHidden("#Create")
    }
    const CreateOutbound = (context) => {
        if (context === "transfer") {
            select("#CreateOutbound #title").innerHTML = "Transfer ke Cabang";
            select("#BranchInput").classList.remove('hidden');
            select("#BranchInput #branch_id_destination").setAttribute('required', 'required');
        } else {
            select("#CreateOutbound #title").innerHTML = "Tambah Stok Keluar";
            select("#BranchInput #branch_id_destination").removeAttribute('required');
            select("#BranchInput").classList.add('hidden');
        }
        toggleHidden("#CreateOutbound");
    }
    const toggleContextMenu = (event, id) => {
        event.stopPropagation();

        // Close all other context menus
        document.querySelectorAll('.ContextMenu').forEach(menu => {
            if (menu.id !== id) menu.classList.add('hidden');
        });

        const menu = select(id);
        menu.classList.toggle('hidden');
    }
    document.addEventListener('click', function () {
        document.querySelectorAll('.ContextMenu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });
</script>
@endsection