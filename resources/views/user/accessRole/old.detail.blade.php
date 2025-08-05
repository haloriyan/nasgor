@extends('layouts.user')

@section('title', "Peran " . $role->name)
    
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
            Pengguna
        </a>
        <div class="flex grow"></div>
        @if ($tab == "users" && count($users) > 0)
            <button class="p-3 px-5 rounded-lg text-xs bg-green-500 text-white font-bold" onclick="toggleHidden('#AssignUserModal')">
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
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="RemoveAccessModal">
    <form action="#" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4">
    @csrf
        <div class="flex items-center gap-4 mb-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Hapus Hak Akses</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#RemoveAccessModal')"></ion-icon>
        </div>

        <div class="text-sm text-slate-600">
            Yakin ingin menghapus <span id="user_name"></span> sebagai <span id="role_name"></span> di <span id="branch_name"></span>?
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#RemoveAccessModal')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-red-500 text-white font-medium">Hapus</button>
        </div>
    </form>
</div>

<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="AssignUserModal">
    <form action="{{ route('accessRole.assign') }}" method="POST" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-2 mt-4">
        @csrf
        <input type="hidden" name="role_id" value="{{ $role->id }}">
        <div class="flex items-center gap-4 mb-4">
            <div class="flex flex-col gap-1 grow">
                <h3 class="text-lg text-slate-700 font-medium">Tetapkan Orang</h3>
                <div class="text-xs text-slate-500">Sebagai {{ $role->name }}</div>
            </div>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#AssignUserModal')"></ion-icon>
        </div>

        <input type="hidden" name="user_id" id="user_id">
        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Orang</label>
            <select name="user" id="user" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required onchange="renderBranches(this.value)">
                <option value="">Pilih Pengguna...</option>
                @foreach ($users as $user)
                    <option value="{{ $user }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cabang</label>
            <select name="branch_id" id="branch_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih cabang...</option>
            </select>
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#AssignUserModal')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tetapkan</button>
        </div>
    </form>
</div>

<input type="hidden" id="role_data" value="{{ $role }}">
@endsection

@section('javascript')
<script>
    const role = JSON.parse(select("input#role_data").value);
    
    const removeAccess = (event, item) => {
        let href = event.currentTarget.href;
        item = JSON.parse(item);
        console.log(href, item);
        event.preventDefault();
        toggleHidden("#RemoveAccessModal");
        select("#RemoveAccessModal form").setAttribute('action', href);
        select("#RemoveAccessModal #user_name").innerHTML = item.user.name;
        select("#RemoveAccessModal #branch_name").innerHTML = item.branch.name;
        select("#RemoveAccessModal #role_name").innerHTML = role.name;
    }

    const renderBranches = user => {
        user = JSON.parse(user);
        let dropdown = select("#AssignUserModal #branch_id");
        select("#AssignUserModal #user_id").value = user.id;
        dropdown.innerHTML = "";
        user.available_branches.forEach((branch, b) => {
            let opt = document.createElement('option');
            opt.value = branch.id;
            opt.innerHTML = branch.name;
            dropdown.appendChild(opt);
        })
    }
</script>
@endsection