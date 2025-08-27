<div class="bg-white p-8 rounded-lg shadow">
    <div class="text-xs text-slate-500">Yang diminta oleh cabang lain dari sini</div>

    <div class="min-w-full overflow-hidden overflow-x-auto mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left">Cabang yang Meminta</th>
                    <th scope="col" class="px-6 py-3 text-left">Jumlah</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($incomingRequests as $item)
                    <tr itemid="{{ $item->id }}">
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-2">
                                @if ($item->product->images->count() == 0)
                                    <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <ion-icon name="image-outline" class="text-xl"></ion-icon>
                                    </div>
                                @else
                                    <img 
                                        src="{{ asset('storage/product_images/' . $item->product->images[0]->filename) }}" alt="{{ $item->id }}"
                                        class="w-16 h-16 bg-slate-100 rounded-lg object-cover"
                                    >
                                @endif
                                <div class="text-sm text-slate-600">{{ $item->product->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $item->seeker_branch->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('stock_request.reject', $item) }}" class="p-3 px-4 rounded-lg bg-red-500 text-white flex items-center">
                                    <ion-icon name="close-outline" class="text-lg"></ion-icon>
                                </a>
                                <button class="p-3 px-4 rounded-lg bg-green-500 text-white flex items-center" onclick="chooseItem(this, '{{ base64_encode($item) }}')">
                                    <ion-icon name="checkmark" class="text-lg"></ion-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>