<div class="flex flex-col gap-4 bg-white p-8 rounded-lg shadow shadow-slate-200 mt-8">
    <h3 class="text-xl text-slate-700 font-medium">Produk</h3>
    <div class="min-w-full overflow-hidden overflow-x-auto p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">
                        <ion-icon name="image-outline"></ion-icon>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left">HPP</th>
                    <th scope="col" class="px-6 py-3 text-left">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            @if ($product->images->count() > 0)
                                <img 
                                    src="{{ asset('storage/product_images/' . $product->images[0]->filename) }}" 
                                    alt="{{ $product->name }}" 
                                    class="h-16 aspect-square rounded-lg object-cover"
                                >
                            @else
                                <div 
                                    class="h-16 aspect-square rounded-lg bg-slate-200 flex items-center justify-center"
                                >
                                    <ion-icon name="image-outline" class="text-lg text-slate-700"></ion-icon>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div>{{ $product->name }}</div>
                            <div class="flex items-center gap-2 text-xs mt-2">
                                @foreach ($product->categories as $category)
                                    <div class="bg-primary-transparent text-primary p-1 px-3 rounded-full">
                                        {{ $category->name }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ currency_encode($product->price) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $product->quantity }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-4">
                            <a href="{{ route('product.detail', $product->id) }}" class="bg-primary text-white flex items-center p-2 px-3 rounded-lg">
                                <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                            </a>
                            <button class="bg-red-500 text-white flex items-center p-2 px-3 rounded-lg" onclick="DeleteProduct('{{ $product }}')">
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>