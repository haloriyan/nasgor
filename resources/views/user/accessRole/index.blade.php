@extends('layouts.user')

@section('title', "Hak Akses")
    
@section('content')
<div class="p-8 flex flex-col gap-2">
    <h1 class="text-slate-700 text-xl font-bold">Hak Akses</h1>
    <div class="text-slate-500 text-xs">Atur Bagaimana Pengguna Mengakses Aplikasi</div>

    <div class="grid grid-cols-3 gap-8 mt-4">
        @foreach ($roles as $role)
            <div class="bg-white rounded-lg shadow p-6 group">
                <div class="flex items-center gap-4">
                    <a href="{{ route('accessRole.detail', $role->id) }}"  class="text-lg text-slate-700 font-medium flex grow">{{ ucwords($role->name) }}</a>
                    @if (strtolower($role->name) != "owner")
                        <a href="#" class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-red-500 opacity-0 group-hover:opacity-100">
                            <ion-icon name="trash-outline"></ion-icon>
                        </a>
                    @endif
                </div>
                <a href="{{ route('accessRole.detail', $role->id) }}">
                    <div class="border-t mt-4 pt-4">
                        <div class="flex items-center gap-4 text-xs text-slate-500">
                            <ion-icon name="person-outline" class="text-lg"></ion-icon>
                            {{ $role->accesses->count() }} akses
                        </div>
                        <div class="flex items-center gap-4 text-xs text-slate-500 mt-2">
                            <ion-icon name="cube-outline" class="text-lg"></ion-icon>
                            {{ count(json_decode($role->permissions) ?? []) }} resources
                        </div>
                        {{-- <div class="flex items-center gap-4 text-xs text-slate-500 mt-2">
                            <ion-icon name="storefront" class="text-lg {{ $role->multibranch ? 'text-green-500' : 'text-slate-400' }}"></ion-icon>
                            @if (!$role->multibranch)
                                Tidak
                            @endif
                            Multi Cabang
                        </div> --}}
                    </div>
                </a>
            </div>
        @endforeach
        <div class="bg-primary text-white rounded-lg p-6 cursor-pointer" onclick="toggleHidden('#newRole')">
            <h3 class="text-lg font-medium flex items-center gap-4">
                <ion-icon name="add-outline"></ion-icon>
                Peran Baru
            </h3>
            <div class="text-sm mt-4">
                Buat peran baru dan tetapkan orang ke dalamnya
            </div>
        </div>
    </div>
</div>
@endsection

@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="newRole">
    <form action="{{ route('accessRole.store') }}" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4" method="POST">
    @csrf
        <div class="flex items-center gap-4 mb-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Buat Peran Baru</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#newRole')"></ion-icon>
        </div>
        <input type="hidden" name="multibranch" id="multibranch" value="0">

        <label for="name" class="text-slate-500 text-xs">Nama Peran</label>
        <input type="text" name="name" id="name" class="w-full h-14 rounded-lg bg-slate-100 text-sm text-slate-700 px-4 outline-0" required>

        <div class="flex items-center gap-4 mt-4">
            <div class="text-xs text-slate-500 flex grow">Bisa Akses ke Multi Cabang?</div>
            <div class="p-1 rounded-full bg-slate-200 cursor-pointer" onclick="toggleMultiBranch(this, '#newRole')">
                <div class="h-6 w-6 bg-white rounded-full me-6" id="SwitchDot"></div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#newRole')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-primary text-white font-medium">Buat Peran</button>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    const toggleMultiBranch = (btn, prefix) => {
        let btnClasses = btn.classList;
        let dotClasses = select(`${prefix} #SwitchDot`).classList;
        let newValue = 0;

        if (btnClasses.contains('bg-green-500')) {
            newValue = 0;
            btnClasses.remove('bg-green-500');
            dotClasses.remove('ms-6');
            dotClasses.add('me-6');
        } else {
            newValue = 1;
            btnClasses.add('bg-green-500');
            dotClasses.remove('me-6');
            dotClasses.add('ms-6');
        }

        select(`${prefix} #multibranch`).value = newValue;
    }
</script>
@endsection