<form action="{{ route('branches.basicInfo', $branch->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-8" id="BasicInfo">
    @csrf
    <div class="flex flex-col w-3/12 bg-white border rounded-lg">
        <div class="p-6 px-8 flex items-center gap-4 border-b">
            <ion-icon name="image-outline" class="text-lg text-slate-600"></ion-icon>
            <div class="text-slate-700 font-medium flex grow">Logo</div>
        </div>
        <div class="p-8">
            <div class="border rounded-lg grow bg-slate-200 aspect-square relative flex flex-col gap-2 items-center justify-center bg-cover bg-center" id="imagePreviewEdit" style="background-image: url('{{ asset('storage/branch_icons/' . $branch->icon) }}')">
                @if ($branch->icon == null)
                    <ion-icon name="add-outline" class="text-xl text-slate-700"></ion-icon>
                @endif
                <input type="file" name="icon" class="absolute top-0 left-0 right-0 bottom-0 opacity-0 cursor-pointer" onchange="onChangeImage(this, '#imagePreviewEdit')" required>
            </div>
            <div class="text-xs text-slate-500 mt-2">Klik gambar untuk mengganti</div>
        </div>
    </div>
    <div class="flex flex-col grow bg-white border rounded-lg">
        <div class="p-6 px-8 flex items-center gap-4 border-b">
            <ion-icon name="image-outline" class="text-lg text-slate-600"></ion-icon>
            <div class="text-slate-700 font-medium flex grow">Informasi Dasar</div>
        </div>
        <div class="flex flex-col gap-6 p-8">
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Nama Cabang</label>
                <input type="text" name="name" id="name" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" value="{{ $branch->name }}" required />
            </div>
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Alamat</label>
                <textarea name="address" id="address" rows="4" class="w-full mt-6 outline-none bg-transparent text-sm text-slate-700">{{ $branch->address }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" value="{{ $branch->latitude }}" required />
                </div>
                <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                    <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="w-full h-10 mt-2 outline-none bg-transparent text-sm text-slate-700" value="{{ $branch->longitude }}" required />
                </div>
            </div>
        </div>
    </div>
</form>