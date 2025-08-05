<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="AddProduct">
    <form action="{{ route('sales.detail.product.store', $sales->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-5/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <input type="hidden" name="product_id" id="product_id">
        <input type="hidden" name="addons" id="addons">
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Tambah Produk</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#AddProduct')"></ion-icon>
        </div>

        <div id="ProductSelectorWrapper">
            <div id="ProductSelector"></div>
        </div>
        
        <div id="ProductDetailWrapper" class="hidden">
            <div class="flex items-center gap-4">
                <img id="ProductImage" class="h-20 w-20 rounded-lg bg-slate-100 object-cover">
                <div class="flex flex-col gap-1 basis-32 grow">
                    <h3 id="ProductName" class="text-lg text-slate-700 font-medium"></h3>
                    <div class="flex items-center gap-2">
                        <select name="product_price_id" id="product_price_id" class="text-sm text-slate-700 border p-1 px-2 rounded" required>
                            <option value="">Pilih Harga</option>
                        </select>
                        <div>x</div>
                        <input name="product_quantity" id="product_quantity" value="1" min="1" class="w-20 p-1 px-2 border rounded text-sm text-slate-500 outline-0" />
                    </div>
                </div>
                <button class="p-2 px-4 rounded-full bg-red-500 text-xs text-white font-medium" type="button" onclick="CancelChooseProduct()">
                    Ganti
                </button>
            </div>

            <div id="AddOnArea" class="border-t pt-4 mt-6 flex flex-col gap-4"></div>

            <div class="flex items-center justify-end gap-4 mt-4">
                <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#AddProduct')">Batal</button>
                <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tambahkan</button>
            </div>
        </div>
    </form>
</div>