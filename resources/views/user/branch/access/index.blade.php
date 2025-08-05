@if ($message != "")
    <div class="bg-green-500 text-sm text-white rounded-lg p-4">
        {{ $message }}
    </div>
@endif
<table class="bg-white rounded-lg">
    <thead>
        <tr class="border-b">
            <td class="p-4 px-6 text-sm text-slate-700 font-medium">Staf</td>
            <td class="p-4 px-6 text-sm text-slate-700 font-medium">Peran</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($accesses as $access)
            <tr>
                <td class="p-4 px-6 text-sm text-slate-500">
                    {{ $access->user->name }}
                </td>
                <td class="p-4 px-6 text-sm text-slate-500">
                    {{ $access->role->name }}
                </td>
                <td class="p-4 px-6">
                    <a href="{{ route('accessRole.removeAccess', $access->id) }}" class="bg-red-500 text-white p-2 px-3 rounded-lg">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>