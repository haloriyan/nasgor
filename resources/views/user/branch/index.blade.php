@extends('layouts.user')

@section('title', "Cabang")
    
@section('content')
<div class="p-8 mobile:p-4 flex flex-col gap-8 mobile:gap-4">
    <div class="flex items-center gap-4 p-4 rounded-lg bg-white">
        <form class="flex mobile:flex-row-reverse items-center gap-4 grow">
            <input type="text" class="w-full h-12 outline-0 text-sm text-slate-700 px-4" placeholder="Cari cabang">
            <button>
                <ion-icon name="search-outline" class="text-xl text-slate-500"></ion-icon>
            </button>
        </form>
        <button class="bg-green-500 text-white text-sm font-medium flex items-center gap-4 mobile:gap-2 p-3 px-6 mobile:px-4 rounded-lg" onclick="toggleHidden('#Create')">
            <ion-icon name="add-outline" class="text-xl"></ion-icon>
            Cabang
        </button>
    </div>

    @if ($message != "")
        <div class="bg-green-500 text-white text-sm p-4 rounded-lg">
            {{ $message }}
        </div>
    @endif

    <div class="min-w-full overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left">
                        <ion-icon name="location-outline"></ion-icon>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left">
                        <ion-icon name="people-outline"></ion-icon>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($branches as $branch)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-4">
                                @if ($branch->icon == null)
                                    <div class="w-14 h-14 bg-slate-200 rounded-lg flex items-center justify-center">
                                        <ion-icon name="image-outline" class="text-xl text-slate-600"></ion-icon>
                                    </div>
                                @else
                                    <img
                                        class="w-14 h-14 rounded-lg object-cover"
                                        src="{{ asset('storage/branch_icons/' . $branch->icon) }}"
                                        alt="{{ $branch->name }}"
                                    >
                                @endif
                                <div>{{ $branch->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $branch->address }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $branch->accesses->count() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('branches.detail', $branch->id) }}">
                                    <button class="p-2 px-4 rounded-lg text-white bg-primary flex items-center">
                                        <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                    </button>
                                </a>
                                <button class="p-2 px-4 rounded-lg text-white bg-red-500 flex items-center" onclick="Delete('{{ $branch }}')">
                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('ModalArea')
    
@include('user.branch.create')
@include('user.branch.delete')

@endsection

@section('javascript')
<script>
    const getLocation = () => {
        console.log('getting location...');

        navigator.geolocation.getCurrentPosition(
            res => {
                let coords = res.coords;
                select("#latitude").value = coords.latitude;
                select("#longitude").value = coords.longitude;
            },
            err => {
                console.error('Geolocation error:', err);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    const Delete = data => {
        data = JSON.parse(data);
        select("#Delete #name").innerHTML = data.name;
        select("#Delete #id").value = data.id;
        toggleHidden("#Delete");
    }
</script>
@endsection