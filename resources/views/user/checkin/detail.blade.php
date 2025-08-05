@extends('layouts.user')

@section('title', "Detail")

@php
    use Carbon\Carbon;
    $coordinates = json_decode($check->coordinates, false);
@endphp
    
@section('content')
<div class="flex flex-col gap-8 p-8">
    <div class="bg-white rounded-lg shadow shadow-slate-200 p-8 flex items-center gap-10">
        <a href="{{ route('checkin') }}" class="flex items-center">
            <ion-icon name="arrow-back-outline" class="text-xl text-slate-600"></ion-icon>
        </a>
        <div class="flex flex-col gap-1 grow">
            <div class="text-slate-600 text-lg font-medium">
                Detail Absensi
            </div>
            <div class="text-slate-500 text-xs">
                {{ $check->user->name }}, {{ Carbon::parse($check->created_at)->isoFormat('DD MMMM YYYY') }}
            </div>
        </div>
        <div class="flex flex-col gap-1">
            <div class="text-xs text-slate-500">Durasi</div>
            <div class="text-lg text-slate-600 font-medium">
                {{ floor($check->duration / 60) }} jam
                {{ ($check->duration % 60) }} menit
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-8 items-start">
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-white text-slate-700 border-b font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Staf</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <ion-icon name="person-outline" class="text-slate-500"></ion-icon>
                    <div class="flex grow text-sm text-slate-700">
                        {{ $check->user->name }}
                    </div>
                </div>
                @foreach ($check->user->accesses as $access)
                    <div class="flex items-center gap-4">
                        <ion-icon name="storefront-outline" class="text-slate-500"></ion-icon>
                        <div class="flex grow text-xs text-slate-500">
                            {{ $access->role->name }} di {{ $access->branch->name }}
                        </div>
                    </div>
                @endforeach
                <div class="border-t h-1 mt-4 mb-3"></div>
                <div class="flex items-center gap-4">
                    <ion-icon name="location-outline" class="text-slate-500"></ion-icon>
                    <div class="flex grow justify-end text-sm text-slate-700">
                        ~ {{ floor($check->distance_from_branch / 1000) }} km
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-xs text-slate-500">Lat</div>
                    <div class="flex grow justify-end text-sm text-slate-700">
                        {{ $coordinates->latitude }}
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-xs text-slate-500">Long</div>
                    <div class="flex grow justify-end text-sm text-slate-700">
                        {{ $coordinates->longitude }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-white text-slate-700 border-b font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Masuk</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <ion-icon name="time-outline" class="text-slate-500"></ion-icon>
                    <div class="flex grow text-sm text-slate-700">
                        {{ Carbon::parse($check->in_at)->isoFormat('DD MMMM YYYY - HH:mm:ss') }}
                    </div>
                </div>
                <img 
                    src="{{ asset('storage/check_in_images/' . $check->in_photo) }}" 
                    alt="in"
                    class="w-full aspect-square rounded-xl object-cover"
                >
            </div>
        </div>
        <div class="flex flex-col rounded-lg bg-white">
            <div class="rounded-t-lg bg-white text-slate-700 border-b font-medium p-4 px-8 flex items-center gap-4">
                <div class="flex grow">Keluar</div>
            </div>
            <div class="p-8 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <ion-icon name="time-outline" class="text-slate-500"></ion-icon>
                    <div class="flex grow text-sm text-slate-700">
                        {{ $check->out_at == null ? "-" : Carbon::parse($check->out_at)->isoFormat('DD MMMM YYYY - HH:mm:ss') }}
                    </div>
                </div>
                @if ($check->out_photo)
                    <img 
                        src="{{ asset('storage/check_in_images/' . $check->out_photo) }}" 
                        alt="out"
                        class="w-full aspect-square rounded-xl object-cover"
                    >
                @endif
            </div>
        </div>
    </div>
</div>
@endsection