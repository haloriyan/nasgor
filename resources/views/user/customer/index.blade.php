@extends('layouts.user')

@section('title', "Pelanggan")
    
@section('content')
<div class="p-8 mobile:p-4 flex flex-col gap-4">
    <div class="flex items-center mobile:flex-row-reverse gap-4 w-full p-2 bg-white rounded-lg">
        <!-- Scrollable Tabs -->
        <div class="flex overflow-x-auto gap-2 pr-4 scrollbar-hide max-w-full">
            <a href="?tab=customer" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ ($tab == '' || $tab == 'customer') ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Pelanggan
            </a>
            <a href="?tab=customer_type" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ in_array($tab, ['customer_type', 'customer_type_detail']) ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Tipe Pelanggan
            </a>
            <a href="?tab=review" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == 'review' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Ulasan Pelangan
            </a>
            {{-- <a href="?tab=satisfaction" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ $tab == 'satisfaction' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Kepuasan Pelanggan
            </a> --}}
        </div>

        <!-- Fixed Action Button -->
        <div class="ml-auto shrink-0">
            @if ($tab == "customer")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AddCustomer')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Pelanggan</div>
                    <div class="desktop:hidden text-xs">Pelanggan</div>
                </button>
            @endif
            @if ($tab == "customer_type")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AddEditType')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Tipe Pelanggan</div>
                    <div class="desktop:hidden text-xs">Tipe</div>
                </button>
            @endif
            @if ($tab == "customer_type_detail")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AddCustomerToType')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Pelanggan ke {{ $type->name }}</div>
                    <div class="desktop:hidden text-xs"> ke {{ $type->name }}</div>
                </button>
            @endif
        </div>
    </div>

    @include('user.customer.' . $tab)
</div>
@endsection

@section('ModalArea')

@include('user.customer.create') 
@include('user.customer.delete') 
@include('user.customer.customer_type_add_edit')
@include('user.customer.customer_type_delete')
@include('user.customer.customer_type_rename')

@if ($type != null)
    @include('user.customer.remove_customer_from_type')
    @include('user.customer.customer_type_add_customer')
@endif

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelector.js') }}"></script>
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    let isEditing = false;

    const Cancel = (target) => {
        toggleHidden(target);
        if (isEditing) {
            select("#AddSupplier #title").innerHTML = "Tambah Supplier";
            isEditing = false;
        }
    }

    function toggleContextMenu(event, id) {
        event.stopPropagation();

        // Close all other context menus
        document.querySelectorAll('[id^="ContextMenu"]').forEach(menu => {
            if (menu.id !== id) menu.classList.add('hidden');
        });

        const menu = document.getElementById(id);
        menu.classList.toggle('hidden');
    }

    // Click outside to close
    document.addEventListener('click', function () {
        document.querySelectorAll('[id^="ContextMenu"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    });

    const RemoveCustomerFromType = (event, customer, type) => {
        const link = event.currentTarget;
        event.preventDefault();
        customer = JSON.parse(customer);
        type = JSON.parse(type);
        
        select("#RemoveCustomer #name").innerHTML = customer.name;
        select("#RemoveCustomer #type").innerHTML = type.name;
        select("#RemoveCustomer form").setAttribute('action', link.href);

        toggleHidden("#RemoveCustomer");
    }

    const DeleteCustomer = (event, data) => {
        event.preventDefault();
        const link = event.currentTarget;
        data = JSON.parse(data);
        select("#DeleteCustomer #name").innerHTML = data.name;
        select("#DeleteCustomer form").setAttribute('action', link.href);
        toggleHidden("#DeleteCustomer");
    }
    const DeleteCustomerType = data => {
        data = JSON.parse(data);
        select("#DeleteCustomerType #id").value = data.id;
        select("#DeleteCustomerType #name").innerHTML = data.name;
        toggleHidden("#DeleteCustomerType");
    }

    const types = @json($types);
    new MultiSelector('#CustomerTypeSelector', types, {
		name: 'type_ids',
		label: 'Tipe Pelanggan',
		placeholder: 'Ketik nama tipe...'
	});
    if (select('#CustomerSelector') !== null) {
        new MultiSelectorAPI('#CustomerSelector', [], {
            fetchUrl: '/api/customer/search?branch_id={{ $me->access->branch_id }}&q=',
            name: "customer_ids",
            label: "Pelanggan",
            parseResponse: (data) => data.customers // if the response is { categories: [...] }
        });
    }
</script>
@endsection