<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="Create">
    <form action="{{ route('branches.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Tambah Cabang</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#Create')"></ion-icon>
        </div>

        <div class="flex justify-center items-center gap-4">
            <div class="border rounded-lg max-w-20 grow bg-slate-200 aspect-square relative flex flex-col gap-2 items-center justify-center bg-cover bg-center" id="imagePreviewEdit">
                <ion-icon name="image-outline" class="text-xl text-slate-700"></ion-icon>
                <input type="file" name="icon" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" onchange="onChangeImage(this, '#imagePreviewEdit')" required>
            </div>
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama</label>
            <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
        </div>

        <div class="group border focus-within:border-primary rounded-lg p-2 relative">
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Alamat</label>
            <textarea name="address" id="address" rows="4" class="w-full mt-6 outline-none bg-transparent text-sm text-slate-700"></textarea>
        </div>

        <div class="text-xs text-slate-500">Koordinat</div>
        <div class="grid grid-cols-2 gap-4">
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
            </div>
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" required />
            </div>
        </div>

        <div class="flex justify-end">
            <div class="cursor-pointer text-primary text-xs" onclick="getLocation()">
                Dapatkan Lokasi Sekarang
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#Create')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tambahkan</button>
        </div>
    </form>
</div>
