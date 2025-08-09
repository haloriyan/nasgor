@php
    use Carbon\Carbon;

    $start = Carbon::parse($startDate)->isoFormat('DD MMMM YYYY');
    $end = Carbon::parse($endDate)->isoFormat('DD MMMM YYYY');
@endphp
<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;font-size: 24px;font-weight: medium;">PERGERAKAN STOK {{ $product->name }}</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;font-size: 12px;">Periode : {{ $start }} - {{ $end }}</th>
            <th colspan="2" style="text-align: center;font-size: 12px;">Cabang : {{ $product->branch->name }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Tanggal</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Tipe</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Stok Lama</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Stok Bergerak</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Stok Baru</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movements as $movement)
            <tr>
                <td>{{ Carbon::parse($movement['date'])->isoFormat('DD MMMM YYYY - HH:mm:ss') }}</td>
                <td>
                    @if ($movement['type'] == "inbound")
                        <span color="#22C55E">Masuk</span>
                    @endif
                    @if ($movement['type'] == "outbound")
                        <span color="#EF4444">Keluar</span>
                    @endif
                    @if ($movement['type'] == "opname")
                        <span color="#334155">Opname</span>
                    @endif
                </td>
                <td>
                    {{ @$movements[$m - 1]['quantity'] }}
                </td>
                <td>
                    {{ @$movements[$m - 1]['movement_amount'] }}
                </td>
                <td>
                    {{ $movement['quantity'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>