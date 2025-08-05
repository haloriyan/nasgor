<div class="bg-white rounded-lg p-8 shadow shadow-slate-200 mt-2">
    <div class="min-w-full overflow-hidden overflow-x-auto p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left flex items-center gap-4">
                        <ion-icon name="storefront-outline"></ion-icon>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left">Email</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($role->accesses as $access)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $access->branch->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $access->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $access->user->email }}</td>
                        
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <button class="p-1 px-3 rounded-lg flex items-center bg-red-500 text-white">
                                <ion-icon name="close-outline" class="text-lg"></ion-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>