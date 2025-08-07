<div class="flex flex-col gap-4 bg-white p-8 rounded-lg shadow shadow-slate-200 mt-8">
    {{-- <h3 class="text-xl text-slate-700 font-medium">Produk</h3> --}}
    <div class="min-w-full overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Addon</th>
                    <th scope="col" class="px-6 py-3 text-left">Harga</th>
                    <th scope="col" class="px-6 py-3 text-left">Berlaku untuk Produk</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($addons as $addon)
                    <tr>
                        <td class="p-3 px-5 text-sm text-slate-600">
                            {{ $addon->name }}
                        </td>
                        <td class="p-3 px-5 text-sm text-slate-600">
                            {{ currency_encode($addon->price) }}
                        </td>
                        <td class="p-3 px-5 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                @foreach ($addon->products as $product)
                                    <div class="text-xs text-slate-700 bg-slate-200 rounded p-2 px-3 flex items-center gap-2">
                                        {{ $product->name }}
                                        <a href="{{ route('product.removeAddOn', [$product->id, $addon->id]) }}" class="bg-red-500 text-white w-4 h-4 flex items-center justify-center rounded-full" onclick="RemoveAddOn()">
                                            <ion-icon name="close-outline" class="text-lg"></ion-icon>
                                        </a>
                                    </div>
                                @endforeach
                                <a href="{{ route('product.addon.addProduct', $addon->id) }}" class="bg-blue-100 p-2 px-3 rounded-lg flex items-center" onclick="AddProductToAddOn(event, '{{ $addon }}')">
                                    <ion-icon name="add-outline" class="text-primary"></ion-icon>
                                </a>
                            </div>
                        </td>
                        <td class="p-3 px-5 text-sm text-slate-600">
                            <div class="flex items-center gap-4">
                                <button class="p-2 px-3 rounded-lg bg-green-500 text-white text-sm font-medium flex items-center" onclick="EditAddOn('{{ $addon }}')">
                                    <ion-icon name="create-outline"></ion-icon>
                                </button>
                                <a href="{{ route('product.addon.delete', $addon->id) }}" class="p-2 px-3 rounded-lg bg-red-500 text-white text-sm font-medium flex items-center" onclick="DeleteAddOn(event, '{{ $addon }}')">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
