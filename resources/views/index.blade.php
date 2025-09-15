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
<body>

<div class="fixed top-0 left-0 right-0 h-20 z-10 px-10 flex items-center gap-12 bg-white/20 backdrop-blur-lg">
    <a href="#">
        <img 
            src="https://aeronef.id/wp-content/uploads/2025/02/logo-light.png" alt="Header Logo"
            class="h-8"
        >
    </a>
    <div class="flex items-center gap-8">
        <a href="#" class="py-2 mobile:text-sm text-primary font-bold border-b border-primary">
            Home
        </a>
        <a href="#" class="py-2 mobile:text-sm text-white font-medium">
            About
        </a>
    </div>
</div>

<div class="absolute top-0 left-0 right-0">
    <div class="w-full h-[700px] relative bg-cover bg-[url('https://aeronef.id/wp-content/uploads/2025/04/Pramugari-Pesawat-Aeronef-Academy-3.jpeg')]">
        okok
    </div>
</div>

</body>
</html>