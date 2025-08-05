<table>
    <thead>
        <tr>
            <th colspan="12" style="text-align: center;font-size: 24px;font-weight: medium;">PURCHASING REPORT</th>
        </tr>
        <tr>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Tanggal</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">No. Pembelian</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Staff</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Supplier</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Produk</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Harga</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Quantity</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Subtotal</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Total Quantity</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;">Nominal Pembelian</th>
            <th style="font-weight: bold;background-color: #eeeeee;color: #333;" colspan="2">Penerima</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchasings as $p => $purchase)
            @foreach ($purchase->items as $i => $item)
                <tr>
                    @if ($i == 0)
                        <td>{{ $purchase->created_at }}</td>
                        <td>{{ $purchase->label }}</td>
                        <td>{{ $purchase->creator->name }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                    @else
                        <td colspan="3"></td>
                    @endif

                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->total_price }}</td>

                    @if ($i == 0)
                        <td>{{ $purchase->total_quantity }}</td>
                        <td>{{ $purchase->total_price }}</td>
                        <td>{{ $purchase->receiver->name }}</td>
                        <td>{{ $purchase->received_at }}</td>
                    @else
                        <td colspan="3"></td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>