
<div class="grid grid-cols-3 gap-8">
    @foreach ($permissions as $perm)
        @if (!in_array(strtolower($perm->key), ['sanctum', 'storage']))
            <div class="flex flex-col gap-4 bg-white rounded-lg p-8 shadow-sm shadow-slate-200">
                <div class="flex items-center gap-4">
                    <div class="flex basis-32 grow mobile:text-sm text-slate-700">{{ strtoupper($perm->key) }}</div>
                    <a href="{{ route('accessRole.togglePermission', [$role->id, $perm->id]) }}" class="p-1 rounded-full {{ in_array($perm->key, $role->permission_keys) ? 'bg-green-500' : 'bg-slate-200' }}">
                        <div class="h-4 w-4 bg-white rounded-full {{ in_array($perm->key, $role->permission_keys) ? 'ms-4' : 'me-4' }}" id="SwitchDot"></div>
                    </a>
                </div>
                <div class="text-xs text-slate-500">
                    {{ $perm->description }}
                </div>
            </div>
        @endif
    @endforeach
</div>
