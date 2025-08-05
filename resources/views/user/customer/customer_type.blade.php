<div class="bg-white rounded-lg p-8 shadow shadow-slate-200 mt-2">
    <div class="min-w-full overflow-hidden overflow-x-auto p-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="text-sm text-slate-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                    <th scope="col" class="px-6 py-3 text-left">Tipe</th>
                    <th scope="col" class="px-6 py-3 text-left">Jumlah Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($types as $type)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700 w-20">
                            <div class="h-2 w-2 rounded-full" style="background-color: {{ $type->color }}"></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            <div class="flex items-center gap-2 cursor-pointer" onclick="RenameCustomerType('{{ $type }}')">
                                {{ $type->name }}
                                <ion-icon name="create-outline" class="text-primary"></ion-icon>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $type->customers->count() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 flex items-center gap-2">
                            <a href="?tab=customer_type_detail&type_id={{ $type->id }}" class="p-2 px-3 rounded-lg flex items-center bg-primary text-white">
                                <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                            </a>
                            <button class="p-2 px-3 rounded-lg flex items-center bg-red-500 text-white" onclick="DeleteCustomerType('{{ $type }}')">
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>