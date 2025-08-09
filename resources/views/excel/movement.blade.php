@php
    use Carbon\Carbon;

    $start = Carbon::parse($startDate)->isoFormat('DD MMMM YYYY');
    $end = Carbon::parse($endDate)->isoFormat('DD MMMM YYYY');
@endphp
<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;font-size: 24px;font-weight: medium;">PERGERAKAN STOK</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;font-size: 12px;">Periode : {{ $start }} - {{ $end }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Produk</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Cabang</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Stok Aktual</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Masuk</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Keluar</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Opname</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->branch->name }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->movements['inbound'] }}</td>
                <td>{{ $product->movements['outbound'] }}</td>
                <td>{{ $product->movements['opname'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>