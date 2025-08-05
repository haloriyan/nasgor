@extends('layouts.user')

@section('title', "Absensi")

@php
    use Carbon\Carbon;
@endphp
    
@section('content')
<div class="flex flex-col gap-8 p-8">
    <div class="bg-white rounded-lg shadow shadow-slate-200 p-4 flex items-center gap-4">
        <form class="flex items-center gap-2 grow">
            <button class="flex items-center">
                <ion-icon name="search-outline" class="text-xl text-slate-600"></ion-icon>
            </button>
            <input type="text" name="q" class="w-full h-12 outline-0 text-sm text-slate-700 px-4" placeholder="Cari berdasarkan nama" value="{{ $request->q }}">
            @if ($request->q != "")
                <a href="{{ route('checkin') }}" class="flex items-center">
                    <ion-icon name="close-outline" class="text-xl text-red-500"></ion-icon>
                </a>
            @endif
        </form>
        <div class="flex flex-col w-3/12">
            <div class="text-xs text-slate-500">Rentang Tanggal</div>
            <div class="flex items-center">
                <input type="text" id="dateRangePicker" class="h-10 outline-0 text-sm text-slate-600 w-full">
                <ion-icon name="chevron-down-outline"></ion-icon>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4 bg-white p-8 rounded-lg shadow shadow-slate-200">
        <div class="min-w-full overflow-hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="person-outline"></ion-icon>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="storefront-outline"></ion-icon>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">Masuk</th>
                        <th scope="col" class="px-6 py-3 text-left">Keluar</th>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="time-outline"></ion-icon>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($checkins as $check)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $check->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $check->branch->name }} ~ {{ floor($check->distance_from_branch / 1000) }}km
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ Carbon::parse($check->in_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                @if ($check->out_at == null)
                                    -
                                @else
                                    {{ Carbon::parse($check->out_at)->isoFormat('DD MMMM YYYY, HH:mm:ss') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ floor($check->duration / 60) }} jam {{ $check->duration % 60 }} menit
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <a href="{{ route('checkin.detail', $check->id) }}" class="bg-primary text-white text-sm p-2 px-3 rounded-lg flex items-center justify-center">
                                    <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $checkins->links() }}
    </div>
</div>
@endsection