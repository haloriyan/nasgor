@extends('layouts.user')

@section('title', $code)
    
@section('ModalArea')
<div class="fixed top-0 left-0 right-0 bottom-0 bg-white p-8 flex items-center justify-center z-30">
    <h1 class="text-[240px] mobile:text-[180px] font-bold text-slate-100 tracking-widest">{{ $code }}</h1>
</div>
<div class="fixed top-0 left-0 right-0 bottom-0 p-8 flex flex-col gap-4 items-center justify-center z-40">
    <h1 class="text-center text-5xl mobile:text-2xl font-bold text-slate-700">{{ $error['title'] }}</h1>
    <div class="text-center mobile:text-sm text-slate-600">{{ $error['description'] }}</div>
    <div class="h-4"></div>
    <a href="{{ route('dashboard') }}" class="bg-primary p-3 px-6 rounded-lg text-white text-sm font-medium">
        Kembali ke Dashboard
    </a>
</div>
@endsection