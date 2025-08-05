<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="AddSupplier">
    <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-8/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Tambah Supplier</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="Cancel('#AddSupplier')"></ion-icon>
        </div>

        <div class="flex mobile:flex-col items-start gap-8">
            <div class="flex w-3/12 mobile:w-full gap-4">
                <div class="border rounded-lg w-full mobile:w-6/12 bg-slate-200 aspect-square relative flex flex-col gap-2 items-center justify-center bg-cover bg-center" id="imagePreview">
                    <ion-icon name="image-outline" class="text-3xl text-slate-700"></ion-icon>
                    <input type="file" name="photo" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" onchange="onChangeImage(this, '#imagePreview')">
                </div>
            </div>
            <div class="flex flex-col grow mobile:w-full gap-4">
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama Supplier</label>
                    <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama PIC</label>
                    <input type="text" name="pic_name" id="pic_name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Telepon</label>
                    <input type="text" name="phone" id="phone" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Alamat</label>
                    <textarea name="address" id="address" rows="4" class="w-full mt-6 outline-none bg-transparent text-sm text-slate-700"></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="Cancel('#AddSupplier')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Submit</button>
        </div>
    </form>
</div>