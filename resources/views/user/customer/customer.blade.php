<div class="desktop:hidden flex flex-col gap-4">
    @foreach ($customers as $index => $customer)
        <div class="bg-white rounded-lg p-6 shadow shadow-slate-200 group relative">
            <div class="flex items-start gap-4">
                <div class="flex flex-col grow gap-1">
                    <div class="text-lg text-slate-700 font-medium">{{ $customer->name }}</div>
                    <div class="flex items-center gap-2">
                        @foreach ($customer->types as $type)
                            <div class="p-1 px-3 rounded-full text-xs text-white" style="background-color: {{ $type->color }}">
                                {{ $type->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Ellipsis Button -->
                <div onclick="toggleContextMenu(event, 'ContextMenu-{{ $index }}')" class="bg-slate-200 text-slate-600 rounded-lg w-8 h-8 flex items-center justify-center cursor-pointer">
                    <ion-icon name="ellipsis-horizontal"></ion-icon>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <ion-icon name="call-outline" class="text-slate-500"></ion-icon>
                <div class="text-slate-700 text-sm">{{ $customer->phone }}</div>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <ion-icon name="mail-outline" class="text-slate-500"></ion-icon>
                <div class="text-slate-700 text-sm">{{ $customer->email }}</div>
            </div>

            <!-- Context Menu -->
            <div id="ContextMenu-{{ $index }}" class="absolute top-12 right-4 bg-white rounded border shadow-lg hidden z-10 min-w-[140px] p-2 text-sm text-slate-600">
                {{-- <div class="hover:bg-slate-100 px-3 py-2 rounded cursor-pointer">Edit</div> --}}
                <a href="{{ route('customer.delete', $customer->id) }}" onclick="DeleteCustomer(event, '{{ $customer }}')" class="hover:bg-slate-100 px-3 py-2 rounded cursor-pointer text-red-500">Hapus</a>
            </div>
        </div>
    @endforeach
</div>
<div class="bg-white rounded-lg p-8 shadow shadow-slate-200 mt-2 mobile:hidden">
    <div class="min-w-full overflow-hidden overflow-x-auto p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left">Email</th>
                    <th scope="col" class="px-6 py-3 text-left">No. Telepon</th>
                    <th scope="col" class="px-6 py-3 text-left">Tipe</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($customers as $customer)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->phone }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-2">
                            @foreach ($customer->types as $type)
                                <div class="p-1 px-3 rounded-full text-xs text-white font-medium" style="background-color: {{ $type->color }}">
                                    {{ $type->name }}
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <a href="{{ route('customer.delete', $customer->id) }}" onclick="DeleteCustomer(event, '{{ $customer }}')" class="p-2 px-3 rounded-lg flex items-center justify-center bg-red-500 text-white">
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>