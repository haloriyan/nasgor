@extends('layouts.user')

@section('title', "Pengguna")
    
@section('content')
<div class="p-8 flex flex-col gap-8">
    <div class="flex items-center gap-4 p-4 rounded-lg bg-white">
        <form class="flex items-center gap-4 grow">
            <input type="text" class="w-full h-12 outline-0 text-sm text-slate-700 px-4" placeholder="Cari pengguna">
            <button>
                <ion-icon name="search-outline" class="text-xl text-slate-500"></ion-icon>
            </button>
        </form>
        <button class="bg-green-500 text-white text-sm font-medium flex items-center gap-4 p-3 px-6 rounded-lg" onclick="toggleHidden('#create')">
            <ion-icon name="add-outline" class="text-xl"></ion-icon>
            Pengguna
        </button>
    </div>

    <div class="min-w-full overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left">Peran</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $u => $user)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-2">
                                @foreach ($user->accesses as $item)
                                    <div class="bg-slate-200 p-2 px-3 rounded-lg text-xs text-slate-600">
                                        {{ ucwords($item->role->name) }} di {{ $item->branch->name }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-sm text-slate-700 flex gap-2">
                            <button class="bg-green-500 text-white p-1 px-4 font-medium text-lg">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <button class="bg-red-500 text-white p-1 px-4 font-medium text-lg">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('ModalArea')
@if ($message != "")
    <div class="fixed bottom-10 right-10 p-4 mobile:p-2 mobile:px-4 rounded-lg bg-green-500 text-white text-sm mobile:text-xs">
        {{ $message }}
    </div>
@endif
<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="create">
    <form action="{{ route('users.store') }}" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4" method="POST">
        @csrf
        <div class="flex items-center gap-4 mb-4">
            <h3 class="text-lg text-slate-700 font-medium flex grow">Tambah Pengguna Baru</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#create')"></ion-icon>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama</label>
            <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
        </div>
        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Username</label>
            <input type="text" name="email" id="email" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
        </div>
        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Password</label>
            <input type="password" name="password" id="password" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cabang</label>
            <select name="branch_id" id="branch_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih cabang...</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Peran</label>
            <select name="role_id" id="role_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih peran...</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ ucwords($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#create')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-primary text-white font-medium">Tambahkan</button>
        </div>
    </form>
</div>

@endsection

@section('javascript')
<script>
    const assignRole = (user) => {
        console.log(user);
        user = JSON.parse(user);
        
        select("#AssignRole #user_name").innerHTML = user.name;
        select("#AssignRole #user_id").value = user.id;
        toggleHidden("#AssignRole");
    }
</script>
@endsection