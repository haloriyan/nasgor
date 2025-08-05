<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {!! json_encode(config('tailwind')) !!}
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        div, aside, header { transition: 0.4s; }
        body {
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-weight: 400;
        }
    </style>
    @yield('head')
</head>
<body class="bg-slate-100">
    
<div class="absolute top-0 left-0 right-0 flex justify-center">
    <div class="w-[650px] flex flex-col gap-4">
        @yield('content')
        <div class="h-[200px]"></div>
    </div>
</div>

<div class="fixed bottom-0 left-0 right-0 flex justify-center hidden">
    <div class="w-[650px] bg-white rounded-t-lg border-t p-6 flex items-center justify-end gap-4">
        <button class="bg-primary text-white text-sm font-medium p-2 px-4 rounded-lg">
            Tulis Ulasan
        </button>
    </div>
</div>

<script>
    const select = dom => document.querySelector(dom);
    const selectAll = dom => document.querySelectorAll(dom);
</script>
@yield('javascript')

</body>
</html>