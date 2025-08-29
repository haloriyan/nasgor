<div class="flex mobile:flex-col gap-4 mt-6">
    <div class="flex flex-col gap-4 grow bg-white p-8 rounded-lg shadow shadow-slate-200">
        <div class="flex items-center gap-4">
            <h3 class="text-xl text-slate-700 font-medium flex grow">List Kategori</h3>
            <button class="p-3 px-4 rounded-lg bg-green-500 text-white text-sm font-medium flex items-center gap-3" onclick="toggleHidden('#AddCategory')">
                <ion-icon name="add-outline"></ion-icon>
                <div class="mobile:hidden">Kategori</div>
            </button>
        </div>
        <div class="min-w-full overflow-hidden overflow-x-auto p-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="text-sm text-slate-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <ion-icon name="image-outline"></ion-icon>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left">Tampil di POS</th>
                        <th scope="col" class="px-6 py-3 text-left">Request Pembelian</th>
                        <th scope="col" class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                @if ($category->image != null)
                                    <img 
                                        src="{{ asset('storage/category_images/' . $category->image) }}" 
                                        alt="{{ $category->name }}"
                                        class="h-12 rounded-lg object-cover aspect-square"
                                    >
                                @else
                                    <div class="h-12 rounded-lg aspect-square bg-slate-200"></div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $category->products->count() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="flex">
                                    <a href="{{ route('product.category.togglePos', [$category->id]) }}" class="p-1 rounded-full {{ $category->pos_visibility ? 'bg-green-500' : 'bg-slate-200' }}">
                                        <div class="h-4 w-4 bg-white rounded-full {{ $category->pos_visibility ? 'ms-4' : 'me-4' }}" id="SwitchDot"></div>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="flex">
                                    <a href="{{ route('product.category.toggleRequestable', [$category->id]) }}" class="p-1 rounded-full {{ $category->requestable ? 'bg-green-500' : 'bg-slate-200' }}">
                                        <div class="h-4 w-4 bg-white rounded-full {{ $category->requestable ? 'ms-4' : 'me-4' }}" id="SwitchDot"></div>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-4">
                                <button class="p-2 px-3 rounded-lg bg-green-500 text-white flex items-center" onclick="EditCategory('{{ $category }}')">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </button>
                                <button class="p-2 px-3 rounded-lg bg-red-500 text-white flex items-center" onclick="DeleteCategory('{{ $category }}')">
                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form action="{{ route('product.category.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-4/12 bg-white p-8 rounded-lg shadow shadow-slate-200 hidden" id="AddCategory">
        @csrf
        <h3 class="text-lg text-slate-700 font-medium" id="title">Tambah Kategori Baru</h3>
        <input type="hidden" name="id" id="id">
        <div class="flex items-center gap-4">
            <div class="flex flex-col gap-1 grow">
                <div class="text-sm text-slate-600">Gambar</div>
                <div class="text-xs text-slate-500 italic">opsional</div>
            </div>
            <div class="border rounded-lg h-16 bg-slate-200 aspect-square relative flex flex-col gap-2 items-center justify-center bg-cover bg-center" id="imagePreviewEdit">
                <ion-icon name="image-outline" class="text-xl text-slate-700"></ion-icon>
                <input type="file" name="image" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" onchange="onChangeImage(this, '#imagePreviewEdit')">
            </div>
        </div>
        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama</label>
            <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
        </div>

        <div class="flex justify-end gap-4">
            <button class="bg-slate-200 rounded-lg text-xs text-slate-700 font-medium p-3 px-5 hidden" type="button" id="cancel" onclick="CancelEditCategory()">
                Batal
            </button>
            <button class="bg-green-500 rounded-lg text-xs text-white font-medium p-3 px-5">
                Tambahkan
            </button>
        </div>
    </form>
</div>