@extends('layouts.user')

@section('title', "Pembelian")

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
    
@section('content')
<div class="p-8">
    <div class="flex items-center w-full p-2 bg-white rounded-lg">
        <a href="?tab=draft" class="p-3 px-6 rounded-lg text-sm {{ ($tab == '' || $tab == 'draft') ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
            Butuh Diproses
        </a>
        <a href="?tab=received" class="p-3 px-6 rounded-lg text-sm {{ $tab == 'received' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
            Telah Diproses
        </a>
        <div class="flex grow"></div>
        <button class="p-3 px-4 rounded-lg bg-primary text-white text-xs font-medium flex items-center gap-2" onclick="toggleHidden('#CreatePurchasing')">
            <ion-icon name="add-outline" class="text-xl"></ion-icon>
            Tambah
        </button>
    </div>

    @if ($message != "")
        <div class="bg-green-500 rounded-lg p-4 text-white text-sm">
            {{ $message }}
        </div>
    @endif

    <div class="flex flex-col gap-4 bg-white p-8 rounded-lg shadow shadow-slate-200 mt-8">
        <div class="min-w-full overflow-hidden overflow-x-auto p-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">No. Pembelian</th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">Supplier</th>
                        <th scope="col" class="px-6 py-3 text-left">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left">Nominal</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($purchasings as $purchase)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <a href="{{ route('purchasing.detail', $purchase->id) }}" class="text-primary font-medium">
                                    {{ $purchase->label }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ Carbon::parse($purchase->created_at)->isoFormat('DD MMMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $purchase->total_quantity }} item(s)
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ currency_encode($purchase->total_price) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- @include('user.purchasing.' . $tab) --}}
</div>
@endsection

@section('ModalArea')
@include('user.purchasing.create')
@endsection

@section('javascript')
<script>
    // 
</script>
@endsection