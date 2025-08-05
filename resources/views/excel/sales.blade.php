<table>
    <thead>
        <tr>
            <th colspan="12" style="text-align: center; font-size: 24px; font-weight: medium;">SALES REPORT</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Tanggal</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">No. Invoice</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Cabang</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Staff</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Produk</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Harga</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Qty</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Subtotal</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Total Qty</th>
            <th style="font-weight: bold; background-color: #eeeeee; color: #333;">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            @foreach ($sale->items as $i => $item)
                <tr>
                    @if ($i == 0)
                        <td>{{ $sale->created_at }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->branch->name }}</td>
                        <td>{{ $sale->user->name }}</td>
                    @else
                        <td colspan="4"></td>
                    @endif

                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->total_price }}</td>

                    @if ($i == 0)
                        <td>{{ $sale->total_quantity }}</td>
                        <td>{{ $sale->total_price }}</td>
                    @else
                        <td colspan="2"></td>
                    @endif
                </tr>

                {{-- Addons rows --}}
                @foreach ($item->addons ?? [] as $addon)
                    <tr>
                        <td colspan="4"></td>
                        <td>↳ {{ $addon->addon->name }}</td>
                        <td>{{ $addon->price }}</td>
                        <td>{{ $addon->quantity }}</td>
                        <td>{{ $addon->total_price }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
