<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    {{-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> --}}
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
        /* Tailwind plugin or global CSS */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

    </style>
    @yield('head')
</head>
<body class="bg-slate-100">

@php
    $me = me();
    $route = Route::currentRouteName();
    $routes = explode(".", $route);
@endphp

<div class="fixed top-0 left-0 right-0 z-20 flex items-center">
    <a href="{{ route('dashboard') }}" class="w-72 h-20 flex gap-4 items-center justify-center bg-white mobile:hidden" id="LeftHeader">
        {{-- <img src="#" alt="Logo Heaedr" class="h-12 w-12 bg-slate-200 rounded-lg"> --}}
        {{-- {!! logo() !!} --}}
        <h1 class="text-slate-700 font-bold text-sm">{{ env('APP_NAME') }}</h1>
    </a>
    <div class="bg-white h-20 flex items-center gap-4 grow px-10 border-b" id="header">
        <div class="h-12 aspect-square flex items-center justify-start cursor-pointer" onclick="toggleSidebar()">
            <ion-icon name="grid-outline" class="text-slate-700 mobile:text-xl"></ion-icon>
        </div>
        <div class="flex flex-col grow">
            <div class="text-xl mobile:text-sm font-bold text-slate-700">@yield('title')</div>
            @yield('subtitle')
        </div>
        @if (!in_array($routes[0], ['dashboard', 'branches', 'sales_report', 'purchasing_report', 'movement_report']))
            <div class="bg-slate-200 rounded-lg p-2 px-4 flex items-center gap-4 cursor-pointer" onclick="toggleHidden('#BranchSwitcher')">
                <div class="pe-4">
                    <div class="text-slate-500 text-[10px]">Cabang :</div>
                    <div class="text-slate-600 font-medium text-xs mt-1">
                        {{ $me->access->branch->name }}
                    </div>
                </div>
                <ion-icon name="chevron-down"></ion-icon>
            </div>
        @endif
        @yield('header.right')
    </div>
</div>

<div class="fixed top-20 left-0 mobile:left-[-100%] bottom-0 w-72 mobile:w-full z-20 bg-white shadow p-4 overflow-y-auto" id="sidebar">
    @php
        $routeName = Route::currentRouteName();
        $routes = explode(".", $routeName);
    @endphp
    <a href="{{ route('dashboard') }}" class="flex items-center gap-4 {{ $routeName == 'dashboard' ? 'bg-primary-transparent text-primary' : 'text-slate-500' }}">
        <div class="h-12 w-1 {{ $routeName == 'dashboard' ? 'bg-primary' : 'bg-white' }}"></div>
        <ion-icon name="home-outline"></ion-icon>
        <div class="text-sm flex">Dashboard</div>
    </a>

    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ in_array(@$routes[0], ['product', 'inventory']) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ in_array(@$routes[0], ['product', 'inventory']) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="cube-outline" class="{{ in_array(@$routes[0], ['product', 'inventory']) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ in_array(@$routes[0], ['product', 'inventory']) ? 'text-primary' : '' }}">Produk & Inventori</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ in_array(@$routes[0], ['product', 'inventory']) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('product') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'product' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'product' ? 'text-primary' : '' }}">Produk</div>
            </a>
            <a href="{{ route('inventory') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'inventory' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'inventory' ? 'text-primary' : '' }}">Inventory</div>
            </a>
        </div>
    </div>
    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ in_array(@$routes[0], ['customer', 'supplier']) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ in_array(@$routes[0], ['customer', 'supplier']) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="people-outline" class="{{ in_array(@$routes[0], ['customer', 'supplier']) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ in_array(@$routes[0], ['customer', 'supplier']) ? 'text-primary' : '' }}">Pelanggan & Supplier</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ in_array(@$routes[0], ['customer', 'supplier']) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('customer') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'customer' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'customer' ? 'text-primary' : '' }}">Pelanggan</div>
            </a>
            <a href="{{ route('supplier') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'supplier' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'supplier' ? 'text-primary' : '' }}">Supplier</div>
            </a>
        </div>
    </div>
    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ in_array(@$routes[0], ['purchasing', 'sales']) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ in_array(@$routes[0], ['purchasing', 'sales']) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="cash-outline" class="{{ in_array(@$routes[0], ['purchasing', 'sales']) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ in_array(@$routes[0], ['purchasing', 'sales']) ? 'text-primary' : '' }}">Transaksi</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ in_array(@$routes[0], ['purchasing', 'sales']) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('sales') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'sales' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'sales' ? 'text-primary' : '' }}">Penjualan</div>
            </a>
            <a href="{{ route('purchasing') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'purchasing' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'purchasing' ? 'text-primary' : '' }}">Pembelian</div>
            </a>
        </div>
    </div>

    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ (in_array(@$routes[0], ['accessRole', 'users', 'checkin'])) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ (in_array(@$routes[0], ['accessRole', 'users', 'checkin'])) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="key-outline" class="{{ (in_array(@$routes[0], ['accessRole', 'users', 'checkin'])) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ (in_array(@$routes[0], ['accessRole', 'users', 'checkin'])) ? 'text-primary' : '' }}">Manajemen Staf</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ (in_array(@$routes[0], ['accessRole', 'users', 'checkin'])) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('users') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="people-outline" class="{{ @$routes[0] == 'users' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'users' ? 'text-primary' : '' }}">Pengguna</div>
            </a>
            <a href="{{ route('accessRole') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="accessibility-outline" class="{{ @$routes[0] == 'accessRole' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'accessRole' ? 'text-primary' : '' }}">Peran & Hak Akses</div>
            </a>
            <a href="{{ route('checkin') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="calendar-outline" class="{{ @$routes[0] == 'checkin' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'checkin' ? 'text-primary' : '' }}">Absensi</div>
            </a>
        </div>
    </div>

    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ (in_array(@$routes[0], ['branches'])) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ (in_array(@$routes[0], ['branches'])) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="storefront-outline" class="{{ (in_array(@$routes[0], ['branches'])) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ (in_array(@$routes[0], ['branches'])) ? 'text-primary' : '' }}">Manajemen Cabang</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ (in_array(@$routes[0], ['branches'])) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('branches') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="{{ @$routes[0] == 'branches' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'branches' ? 'text-primary' : '' }}">Cabang</div>
            </a>
        </div>
    </div>
    <div class="group relative">
        <a href="#" class="flex items-center gap-4 text-slate-500 {{ (in_array(@$routes[0], ['sales_report', 'purchasing_report', 'expense_report', 'movement_report'])) ? 'bg-primary-transparent text-primary' : '' }}">
            <div class="h-12 w-1 {{ (in_array(@$routes[0], ['sales_report', 'purchasing_report', 'expense_report', 'movement_report'])) ? 'bg-primary' : 'bg-white' }}"></div>
            <ion-icon name="bar-chart-outline" class="{{ (in_array(@$routes[0], ['sales_report', 'purchasing_report', 'expense_report', 'movement_report'])) ? 'text-primary' : '' }}"></ion-icon>
            <div class="text-sm flex grow {{ (in_array(@$routes[0], ['sales_report', 'purchasing_report', 'expense_report', 'movement_report'])) ? 'text-primary' : '' }}">Laporan</div>
            <ion-icon name="chevron-down-outline" class="me-4"></ion-icon>
        </a>
        <div class="{{ (in_array(@$routes[0], ['sales_report', 'purchasing_report', 'expense_report', 'movement_report'])) ? 'flex' : 'hidden' }} group-hover:flex flex-col mt-2 mb-2">
            <a href="{{ route('sales_report') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'sales_report' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'sales_report' ? 'text-primary' : '' }}">Penjualan</div>
            </a>
            <a href="{{ route('purchasing_report') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'purchasing_report' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'purchasing_report' ? 'text-primary' : '' }}">Pembelian</div>
            </a>
            <a href="{{ route('movement_report') }}" class="flex items-center gap-4 text-slate-500">
                <div class="h-10 w-1 bg-white"></div>
                <ion-icon name="ellipse-outline" class="text-[8px] {{ @$routes[0] == 'movement_report' ? 'text-primary' : '' }}"></ion-icon>
                <div class="text-sm flex grow {{ @$routes[0] == 'movement_report' ? 'text-primary' : '' }}">Pergerakan Stok</div>
            </a>
        </div>
    </div>
</div>

<div class="absolute top-20 left-72 mobile:left-0 right-0 z-10" id="content">
    {{-- {{ $me->branches }} --}}
    @yield('content')
</div>

@yield('ModalArea')

<div class="fixed top-0 left-0 right-0 bottom-0 p-20 mobile:p-10 bg-slate-200 z-20 hidden" id="BranchSwitcher">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl text-slate-700 font-bold flex grow">Pilih Cabang</h1>
        <ion-icon name="close-outline" class="text-3xl text-slate-700 cursor-pointer" onclick="toggleHidden('#BranchSwitcher')"></ion-icon>
    </div>

    <div class="grid grid-cols-4 mobile:grid-cols-1 gap-8 mt-8">
        @foreach ($me->accesses as $access)
            <a href="{{ route('branch.switch', $access->id) }}" class="bg-white p-8 rounded-xl border {{ $me->current_access == $access->id ? 'border-primary' : '' }}">
                <h3 class="text-lg text-slate-700 font-medium">{{ $access->branch->name }}</h3>
            </a>
        @endforeach
        {{-- @foreach ($me->branches as $branch)
            <a href="{{ route('branch.switch', $branch->id) }}" class="bg-white p-8 rounded-xl border {{ $me->branch_id == $branch->id ? 'border-primary' : '' }}">
                <h3 class="text-lg text-slate-700 font-medium">{{ $branch->name }}</h3>
            </a>
        @endforeach --}}
        {{-- @foreach ($me->accesses as $i => $access)
            <a href="{{ route('branch.switch', $access->branch->id) }}" class="bg-white p-8 rounded-xl border {{ $access->id == $me->access_id ? 'border-primary' : '' }}">
                <h3 class="text-lg text-slate-700 font-medium">{{ $access->branch->name }}</h3>
            </a>
        @endforeach --}}
    </div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script>
    const select = dom => document.querySelector(dom);
    const selectAll = dom => document.querySelectorAll(dom);
    const header = select("#header");
    const LeftHeader = select("#LeftHeader");
    const sidebar = select("#sidebar");
    const content = select("#content");
    // const ProfileMenu = select("#ProfileMenu");

    // const randomString = (length) => Array.from({ length }, () => Math.random().toString(36)[2]).join('');
    const randomString = (length) => Array.from({ length }, (_, i) => i < length / 2 ? String.fromCharCode(97 + Math.floor(Math.random() * 26)) : Math.floor(Math.random() * 10)).join('');

    const toggleSidebar = () => {
        if (window.screen.width < 480) {
            toggleSidebarMobile();
        } else {
            toggleSidebarDesktop();
        }
    }
    const toggleSidebarMobile = () => {
        if (sidebar.classList.contains('mobile:left-0')) {
            sidebar.classList.remove('mobile:left-0');
            sidebar.classList.add('mobile:left-[-100%]')
        } else {
            sidebar.classList.remove('mobile:left-[-100%]')
            sidebar.classList.add('mobile:left-0');
        }
    }
    const toggleSidebarDesktop = () => {
        LeftHeader.classList.toggle('w-0');
        
        if (sidebar.classList.contains('w-72')) {
            // close
            sidebar.classList.add('w-0');
            sidebar.classList.remove('w-72');
            content.classList.add('left-0');
            content.classList.remove('left-72');
            setTimeout(() => {
                sidebar.classList.add('hidden');
            }, 210);
        } else  {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('w-72');
            content.classList.remove('left-0');
            content.classList.add('left-72');
            setTimeout(() => {
                sidebar.classList.remove('w-0');
            }, 10)
        }
    }
    const toggleHidden = target => {
        select(target).classList.toggle('hidden');
    }
    const Currency = (amount) => {
        let props = {};
        props.encode = (prefix = 'Rp') => {                                                               
            let result = '';                                                                              
            let amountRev = amount.toString().split('').reverse().join('');
            for (let i = 0; i < amountRev.length; i++) {
                if (i % 3 === 0) {
                    result += amountRev.substr(i,3)+'.';
                }
            }
            return prefix + ' ' + result.split('',result.length-1).reverse().join('');
        }
        props.decode = () => {
            return parseInt(amount.replace(/,.*|[^0-9]/g, ''), 10);
        }

        return props;
    }
    const onChangeImage = (input, target) => {
        const file = input.files[0];
        const reader = new FileReader();
        const imagePreview = document.querySelector(target);

        reader.onload = function () {
            const source = reader.result;

            // Set image as background
            imagePreview.style.backgroundImage = `url("${source}")`;
            imagePreview.style.backgroundSize = "cover";
            imagePreview.style.backgroundPosition = "center center";

            // Remove placeholder icons (but keep input)
            Array.from(imagePreview.childNodes).forEach(ch => {
                if (ch.tagName !== "INPUT") {
                    ch.remove();
                }
            });

            // If input name ends with [], clone new input
            if (input.name.endsWith("[]")) {
                // Add remove button
                const removeIcon = document.createElement("ion-icon");
                removeIcon.setAttribute("name", "close-circle");
                // removeIcon.className = "text-red-500 text-xl text-white absolute top-1 right-1 cursor-pointer z-10";
                removeIcon.classList.add('text-red-500', 'text-2xl', 'text-white', 'absolute', 'top-1', 'right-1', 'cursor-pointer', 'z-10');
                removeIcon.addEventListener("click", () => {
                    // Only remove if more than one preview exists
                    if (imagePreview.parentNode.querySelectorAll('[id^="imagePreviewEdit"]').length > 1) {
                        imagePreview.remove();
                    }
                });
                imagePreview.appendChild(removeIcon);
                
                const newWrapper = imagePreview.cloneNode(true);
                const newInput = newWrapper.querySelector('input[type="file"]');

                // Reset background and file input
                newWrapper.style.backgroundImage = '';
                newInput.value = '';
                newInput.removeAttribute("required"); // ✅ REMOVE required on clone
                newWrapper.querySelectorAll("*:not(input)").forEach(el => el.remove());

                // Restore placeholder icon
                const placeholderIcon = document.createElement("ion-icon");
                placeholderIcon.setAttribute("name", "image-outline");
                placeholderIcon.className = "text-xl text-slate-700";
                newWrapper.insertBefore(placeholderIcon, newInput);

                // Set new ID and event
                const newId = `imagePreviewEdit-${Date.now()}`;
                newWrapper.id = newId;
                newInput.setAttribute("onchange", `onChangeImage(this, '#${newId}')`);

                // Append new block
                imagePreview.parentNode.appendChild(newWrapper);
            }

        };

        if (file) {
            reader.readAsDataURL(file);
        }
    };

    const onChangeImageOri = (input, target) => {
        let file = input.files[0];
        let reader = new FileReader();
        let imagePreview = select(target);
        
        reader.onload = function () {
            let source = reader.result;
            imagePreview.style.backgroundImage = `url(${source})`;
            imagePreview.style.backgroundSize = "cover";
            imagePreview.style.backgroundPosition = "center center";
            
            Array.from(imagePreview.childNodes.values()).map(ch => {
                if (ch.tagName !== "INPUT") {
                    ch.remove();
                }
            })
        }

        reader.readAsDataURL(file);
    }

    const applyImageToDiv = (target, src) => {
        target.style.backgroundImage = `url("${src}")`;
        target.style.backgroundSize = "cover";
        target.style.backgroundPosition = "center center";
        
        Array.from(imagePreview.childNodes.values()).map(ch => {
            if (ch.tagName !== "INPUT") {
                ch.remove();
            }
        })
    }
    const addFilter = (keyOrObject, value) => {
        const url = new URL(window.location.href);
        const params = url.searchParams;

        if (typeof keyOrObject === 'object' && keyOrObject !== null) {
            // If an object is passed
            Object.entries(keyOrObject).forEach(([key, val]) => {
                if (val === null || val === undefined) {
                    params.delete(key);
                } else {
                    params.set(key, val);
                }
            });
        } else {
            // If single key and value
            if (value === null || value === undefined) {
                params.delete(keyOrObject);
            } else {
                params.set(keyOrObject, value);
            }
        }

        // Build and redirect to the new full URL
        const newUrl = url.origin + url.pathname + '?' + params.toString();
        window.location.href = newUrl;
    };

</script>
@yield('javascript')

</body>
</html>