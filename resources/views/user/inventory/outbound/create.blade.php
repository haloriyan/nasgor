<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="CreateOutbound">
    <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="type" id="type" value="{{ $tab }}">
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Tambah Stok Keluar</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#CreateOutbound')"></ion-icon>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative" id="BranchInput">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cabang Tujuan</label>
            <select name="branch_id_destination" id="branch_id_destination" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                <option value="">Pilih Cabang...</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">
                        {{ $branch->name }}
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
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#CreateOutbound')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tambahkan</button>
        </div>
    </form>
</div>