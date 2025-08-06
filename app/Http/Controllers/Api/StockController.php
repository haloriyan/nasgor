<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function detail($id) {
        $purchasing = StockMovement::where('id', $id)
        ->with(['branch', 'user', 'items.product.images'])
        ->first();

        return response()->json([
            'opname' => $purchasing,
        ]);
    }
    public function updateNotes($id, Request $request) {
        $data = StockMovement::where('id', $id);
        $data->update([
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => "Berhasil mengubah catatan"
        ]);
    }
    public function addProduct($id, Request $request) {
        $controller = new InventoryController();
        $controller->addProduct($id, $request);

        return response()->json([
            'message' => "Berhasil menambah produk"
        ]);
    }
    public function removeProduct($id, $itemID, Request $request) {
        $controller = new InventoryController();
        $controller->removeProduct($id, $itemID);

        return response()->json([
            'message' => "Berhasil menghapus produk"
        ]);
    }
    public function publish($id) {
        $controller = new InventoryController();
        $controller->proceed($id, false);

        return response()->json([
            'message' => "Berhasil mempublikasikan"
        ]);
    }

    public function storeOpname(Request $request) {
        $user = me($request->user('user'));

        $controller = new InventoryController();
        $stock = $controller->store($request, $user, true);
        $stock = StockMovement::where('id', $stock->id)
        ->with(['branch', 'user', 'items.product.images'])
        ->first();

        return response()->json([
            'stock' => $stock
        ]);
    }

    public function movementReport(Request $request) {
        $user = me($request->user('user'));
        $reportController = new ReportController();
        $data = $reportController->stockMovement($request, $user);

        return response()->json([
            'products' => $data['products']
        ]);
    }
    public function movementDetail($productID, Request $request) {
        $me = me($request->user('user'));

        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d H:i:s');

        $product = Product::where('id', $productID)->firstOrFail();
        $quantity = $product->quantity;

        $stocks = StockMovementProduct::where('product_id', $product->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('movement')
            ->orderBy('created_at', 'DESC')
            ->get();

        $inboundSeries = [
            'label' => [],
            'data' => []
        ];
        $outboundSeries = [
            'label' => [],
            'data' => []
        ];

        $movements = [];

        foreach ($stocks as $stock) {
            if ($stock->movement->type === 'inbound') {
                $quantity -= $stock->quantity;
                $inboundSeries['data'][] = $stock->quantity;
                $inboundSeries['label'][] = Carbon::parse($stock->created_at)->isoFormat('DD MMM, HH:mm');
            } elseif ($stock->movement->type === 'outbound') {
                $quantity += $stock->quantity;
                $outboundSeries['data'][] = $stock->quantity;
                $outboundSeries['label'][] = Carbon::parse($stock->created_at)->isoFormat('DD MMM, HH:mm');
            } elseif ($stock->movement->type === 'opname') {
                $quantity = $stock->quantity;
            }

            $movements[] = [
                'quantity' => $quantity,
                'date' => $stock->created_at,
                'movement_amount' => $stock->quantity,
                'type' => $stock->movement->type,
            ];
        }

        $movements = array_reverse($movements); // oldest to newest

        // 🎯 Convert to Wagmi format: [{ timestamp, open, close, low, high }]
        $wagmiSeries = [];
        foreach ($movements as $i => $move) {
            $open = $i > 0 ? $movements[$i - 1]['quantity'] : $move['quantity'];
            $close = $move['quantity'];
            $low = min($open, $close);
            $high = max($open, $close);

            $wagmiSeries[] = [
                'timestamp' => Carbon::parse($move['date'])->timestamp * 1000, // UNIX ms
                'open' => $open,
                'close' => $close,
                'low' => $low,
                'high' => $high
            ];
        }

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
            ],
            'movements' => $movements,
            'candlestick' => $wagmiSeries,
            'inbound' => $inboundSeries,
            'outbound' => $outboundSeries,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
