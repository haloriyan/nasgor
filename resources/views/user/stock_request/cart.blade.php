<form action="{{ route('stock_request.accept') }}" class="fixed bottom-5 right-8 bg-white rounded-lg border w-3/12 p-6 z-40 hidden" id="Cart" method="POST">
    @csrf
    <input type="hidden" name="ids" id="item_ids">
    <input type="hidden" name="origin" value="web">
    <div class="flex items-center gap-4">
        <div class="flex flex-col gap-1 grow">
            <h3 class="text-lg text-slate-700 font-medium">Detail</h3>
            <div class="text-xs text-slate-500">Daftar Produk yang Disetujui</div>
        </div>
        <ion-icon name="remove-outline" class="text-lg text-slate-700 cursor-pointer"></ion-icon>
    </div>
    <div class="flex flex-col gap-4 mt-4" id="CartItemArea"></div>
    <button class="w-full h-12 bg-green-500 text-white text-sm font-medium rounded-lg mt-4 AccBtn">
        Setujui
    </button>
</form>