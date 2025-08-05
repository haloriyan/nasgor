@extends('layouts.auth')

@section('title', "Login")
    
@section('content')
<form action="{{ route('login') }}" method="POST" class="flex flex-col gap-2">
    @csrf
    <h1 class="text-xl text-slate-700 font-medium mb-4">Login</h1>
    <label for="email" class="text-slate-500 text-xs">Email</label>
    <input type="email" name="email" id="email" class="w-full h-14 rounded-lg bg-slate-100 text-sm text-slate-700 px-4 outline-0" required value="admin@admin.com">
    <label for="email" class="text-slate-500 text-xs mt-2">Password</label>
    <input type="password" name="password" id="password" class="w-full h-14 rounded-lg bg-slate-100 text-sm text-slate-700 px-4 outline-0" required value="123456">

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $err)
            <div class="bg-red-100 text-red-500 text-sm p-4 rounded-lg mt-4">
                {{ $err }}
            </div>
        @endforeach
    @endif
    @if ($message != "")
        <div class="bg-green-100 text-green-500 text-sm p-4 rounded-lg mt-4">
            {{ $message }}
        </div>
    @endif
    <button class="mt-4 rounded-lg bg-primary text-white text-sm font-medium w-full h-12">Login</button>
</form>
@endsection