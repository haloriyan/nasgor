<div class="flex flex-col gap-4 bg-white p-8 rounded-lg shadow shadow-slate-200 mt-8">
    <div class="flex items-center gap-8">
        <form class="group border focus-within:border-primary rounded-lg p-2 relative flex flex-col grow">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cari berdasarkan produk</label>
            <div class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="q" id="q" class="w-full h-10 mt-3 outline-none bg-transparent text-sm text-slate-700" required value="{{ $request->q }}" />
                @if ($request->q != "")
                    <a href="?q=" class="text-red-500">
                        <ion-icon name="close-outline" class="text-lg"></ion-icon>
                    </a>
                @endif
                <button>
                    <ion-icon name="search-outline" class="text-slate-700 text-lg"></ion-icon>
                </button>
            </div>
        </form>
        <div>
            <div class="text-xs text-slate-500">Status</div>
            <div class="flex p-2 bg-slate-200 rounded-lg mt-1">
                <a href="?tab={{ $tab }}&status=DRAFT" class="p-2 px-6 rounded-lg text-xs {{ $request->status == "DRAFT" ? 'bg-white text-primary' : 'text-slate-500' }}">
                    DRAFT
                </a>
                <a href="?tab={{ $tab }}&status=" class="p-2 px-6 rounded-lg text-xs {{ $request->status == "" ? 'bg-white text-primary font-medium' : 'text-slate-500' }}">
                    SEMUA
                </a>
                <a href="?tab={{ $tab }}&status=PUBLISHED" class="p-2 px-6 rounded-lg text-xs {{ $request->status == "PUBLISHED" ? 'bg-white text-primary font-medium' : 'text-slate-500' }}">
                    PUBLISHED
                </a>
            </div>
        </div>
    </div>
    <div class="min-w-full overflow-hidden overflow-x-auto mt-2">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">No.</th>
                    <th scope="col" class="px-6 py-3 text-left">Supplier</th>
                    <th scope="col" class="px-6 py-3 text-left">Status</th>
                    <th scope="col" class="px-6 py-3 text-left">
                        <ion-icon name="create-outline"></ion-icon>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inventories as $inventory)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <a href="{{ route('inventory.detail', $inventory->id) }}" class="text-primary font-medium">
                                {{ $inventory->label }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            @if ($inventory->supplier_id == null)
                                -
                            @else
                                {{ $inventory->supplier->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $inventory->status }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $inventory->notes }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>