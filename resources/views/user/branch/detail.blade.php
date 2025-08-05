@extends('layouts.user')

@section('title', "Detail")
    
@section('content')
<div class="p-8 mobile:p-4 flex flex-col gap-4">
    <a href="{{ route('branches') }}" class="flex items-center gap-4 text-slate-500">
        <ion-icon name="arrow-back-outline" class="text-lg"></ion-icon>
        <div class="text-xs">Kembali</div>
    </a>
    <div class="flex items-center mobile:flex-row-reverse gap-4 w-full p-2 bg-white rounded-lg">
        <!-- Scrollable Tabs -->
        <div class="flex overflow-x-auto gap-2 pr-4 scrollbar-hide max-w-full">
            <a href="?tab=detail" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ ($tab == '' || $tab == 'detail') ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Detail
            </a>
            <a href="?tab=access" class="p-3 px-6 mobile:px-4 whitespace-nowrap rounded-lg text-sm mobile:text-xs {{ in_array($tab, ['access', 'customer_type_detail']) ? 'text-primary font-medium bg-primary-transparent' : 'text-slate-600' }}">
                Akses
            </a>
        </div>

        <!-- Fixed Action Button -->
        <div class="ml-auto shrink-0">
            @if ($tab == "detail")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="saveBasicInfo()">
                    <div class="mobile:hidden">Simpan Perubahan</div>
                    <div class="desktop:hidden text-xs">Simpan</div>
                </button>
            @endif
            @if ($tab == "access")
                <button class="bg-green-500 text-sm text-white font-medium p-3 px-4 rounded-lg flex items-center gap-2 whitespace-nowrap" onclick="toggleHidden('#AssignUser')">
                    <ion-icon name="add-outline" class="text-lg"></ion-icon>
                    <div class="mobile:hidden">Tambah Staff</div>
                    <div class="desktop:hidden text-xs">Staff</div>
                </button>
            @endif
        </div>
    </div>

    @include('user.branch.'.$tab.'.index')
</div>
@endsection

@section('ModalArea')

@include('user.accessRole.assign_user')

@endsection

@section('javascript')
<script src="{{ asset('js/MultiSelectorAPI.js') }}"></script>
<script>
    const saveBasicInfo = () => {
        select("form#BasicInfo").submit();
    }

    new MultiSelectorAPI('#UserSelector', [], {
        fetchUrl: '/api/user/search?q=',
        name: "user_ids",
        label: "Staff",
        parseResponse: (data) => data.users // if the response is { categories: [...] }
    });
</script>
@endsection