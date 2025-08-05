@extends('layouts.user')

@section('title', "Detail")
    
@section('content')
<div class="p-8 flex flex-col gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('accessRole') }}">
            <ion-icon name="arrow-back-outline" class="text-xl text-slate-700"></ion-icon>
        </a>
        <h1 class="text-xl text-slate-700 font-bold">{{ ucwords($role->name) }}</h1>
        <div class="flex grow"></div>
        <button class="bg-red-500 text-white text-sm font-medium rounded-lg p-3 px-6">
            Hapus
        </button>
    </div>

    <div class="flex items-center w-full p-2 bg-white rounded-lg">
        <a href="?tab=resources" class="p-3 px-6 rounded-lg text-sm {{ ($tab == '' || $tab == 'resources') ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
            Resource
        </a>
        <a href="?tab=users" class="p-3 px-6 rounded-lg text-sm {{ $tab == 'users' ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
            Akses
        </a>
        <div class="flex grow"></div>
        @if ($tab == "users")
            <button class="p-3 px-5 rounded-lg text-xs bg-green-500 text-white font-bold" onclick="toggleHidden('#AssignUser')">
                Tetapkan Orang
            </button>
        @endif
    </div>

    @include('user.accessRole.' . $tab, [
        'role' => $role,
    ])
</div>
@endsection

@section('ModalArea')
    
@include('user.accessRole.assign_user')

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    new MultiSelectorAPI('#UserSelector', [], {
        fetchUrl: '/api/user/search?q=',
        name: "user_ids",
        label: "Staff",
        parseResponse: (data) => data.users // if the response is { categories: [...] }
    });
</script>
@endsection