@if ($message != "")
    <div class="bg-green p-4 rounded-lg bg-green-500 text-white text-sm mt-6">
        {{ $message }}
    </div>
@endif
<div class="bg-white rounded-lg p-8 shadow shadow-slate-200 mt-2">
    <div class="flex items-center gap-4">
        <a href="{{ route('customer', ['tab' => "customer_type"]) }}">
            <ion-icon name="arrow-back-outline" class="text-xl text-slate-700"></ion-icon>
        </a>
        <div class="text-lg text-slate-700 font-medium flex grow">{{ $type->name }}</div>
        <div class="text-xs text-slate-500">
            Jumlah Pelanggan : {{ $type->customers->count() }}
        </div>
    </div>
    <div class="min-w-full overflow-hidden overflow-x-auto p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left">Email</th>
                    <th scope="col" class="px-6 py-3 text-left">No. Telepon</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($type->customers as $customer)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $customer->phone }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-4">
                            <a href="{{ route('customer.type.remove', [$type->id, $customer->id]) }}" class="p-2 px-3 rounded-lg flex items-center bg-red-500 text-white" onclick="RemoveCustomerFromType(event, '{{ $customer }}', '{{ $type }}')">
                                <ion-icon name="close-outline" class="text-lg"></ion-icon>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>