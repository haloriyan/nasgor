@extends('layouts.user')

@section('title', "Profil")
    
@section('content')
<div class="p-8 flex gap-8">
    <form action="{{ route('profile.save') }}" method="POST" class="bg-white rounded-lg shadow shadow-slate-200 flex flex-col grow">
        @csrf
        <div class="flex items-center gap-4 p-4 px-8 border-b">
            <h2 class="text-lg text-slate-700 font-medium flex grow">Informasi Akun</h2>
            <button class="bg-green-500 text-white text-sm p-2 px-4 rounded-lg font-medium">
                Simpan
            </button>
        </div>
        <div class="p-8 flex flex-col gap-4">
            @if ($message != "")
                <div class="bg-green-500 text-sm text-white rounded-lg p-4">
                    {{ $message }}
                </div>
            @endif
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama</label>
                <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required value="{{ $me->name }}" />
            </div>
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Email</label>
                <input type="email" name="email" id="email" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required value="{{ $me->email }}" />
            </div>
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Ganti Password</label>
                <input type="password" name="password" id="password" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" />
            </div>
            <div class="text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti password</div>
        </div>
    </form>
    <div class="bg-white rounded-lg shadow shadow-slate-200 flex flex-col w-4/12">
        <div class="flex items-center gap-4 p-4 px-8 border-b">
            <h2 class="text-lg text-slate-700 font-medium">Hak Akses</h2>
        </div>
        <div class="p-8">
            sd
        </div>
    </div>
</div>
@endsection