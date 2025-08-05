<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="Create">
    <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="type" id="type" value="{{ $tab }}">
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Terima dari Pembelian</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#Create')"></ion-icon>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative" id="PurchasingInput">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">No. Pembelian</label>
            <select name="purchasing_id" id="purchasing_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih Pembelian...</option>
                @foreach ($purchasings as $purchasing)
                    <option value="{{ $purchasing->id }}">
                        {{ $purchasing->label }} - {{ $purchasing->supplier->name }} - {{ $purchasing->created_at }} 
                    </option>
                @endforeach
            </select>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative" id="SupplierInput">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih Supplier...</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Label</label>
            <input type="text" name="label" id="label" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required value="{{ $tab == "inbound" ? "IN" : "OUT" }}{{ date('YmdHis') }}" />
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Catatan</label>
            <textarea name="notes" id="notes" rows="4" class="w-full mt-6 outline-none bg-transparent text-sm text-slate-700"></textarea>
        </div>
        
        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#Create')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tambahkan</button>
        </div>
    </form>
</div>