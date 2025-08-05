<div class="fixed top-0 left-0 right-0 bottom-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-30" id="AssignUser">
    <form action="{{ route('accessRole.assign') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-10 w-4/12 mobile:w-10/12 flex flex-col gap-4 mt-4">
        @csrf
        @if (@$role)
            <input type="hidden" name="role_id" value="{{ $role->id }}">
        @endif
        @if (@$branch)
            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
        @endif

        <div class="flex items-center gap-4 mb-2">
            <h3 class="text-lg text-slate-700 font-medium flex grow" id="title">Tetapkan Orang</h3>
            <ion-icon name="close-outline" class="cursor-pointer text-3xl" onclick="toggleHidden('#AssignUser')"></ion-icon>
        </div>

        @if (@$branches)
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Cabang</label>
                <select name="branch_id" id="branch_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                    <option value="">Pilih cabang...</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if (@$roles)
            <div class="group border focus-within:border-primary rounded-lg p-2 relative">
                <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">Peran</label>
                <select name="role_id" id="role_id" class="w-full h-10 mt-3 mb-1 outline-none bg-transparent text-sm text-slate-700" required>
                    <option value="">Pilih peran...</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div id="UserSelector"></div>
        
        <div class="flex items-center justify-end gap-4 mt-4">
            <button class="p-3 px-6 rounded-lg text-sm bg-slate-200 text-slate-700" type="button" onclick="toggleHidden('#AssignUser')">Batal</button>
            <button class="p-3 px-6 rounded-lg text-sm bg-green-500 text-white font-medium">Tetapkan</button>
        </div>
    </form>
</div>