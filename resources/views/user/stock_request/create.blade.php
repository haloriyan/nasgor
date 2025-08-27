<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="AddRequest">
    <form action="{{ route('stock_request.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Buat Permintaan Stok</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#AddRequest')"></ion-icon>
        </div>

        <div>
            <div class="text-xs text-slate-500 mb-1">Minta dari Cabang :</div>
            <select name="branch_id" id="branchID" class="w-full h-12 outline-0 text-sm text-slate-600 px-4 border" onchange="changeBranch(this.value)">
                <option value="">Pilih cabang...</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="ProductSelector"></div>
        <div id="QtyArea" class="hidden">
            <div class="text-xs text-slate-500 mb-1">Jumlah :</div>
            <input type="number" name="quantity" min="1" value="1" class="w=full h-12 outline-0 text-sm text-slate-600 px-4 border rounded-lg" required>
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#AddRequest')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Minta</button>
        </div>
    </form>
</div>