<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use App\Models\StockRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $startDate = $request->start_date ?? Carbon::now()->subDays(7);
        $endDate = $request->end_date ?? Carbon::now();
        $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

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
    public function stockRequestStore(Request $request) {
        $user = me($request->user('user'));
        $items = $request->items;
        $branch = $request->branch;
        foreach ($items as $item) {
            $totalPrice = $item['quantity'] * $item['price'];
            $ch = StockRequest::where([
                ['is_accepted', false],
                ['product_id', $item['id']],
                ['seeker_branch_id', $user->access->branch_id],
                ['provider_branch_id', $branch['id']]
            ]);
            $check = $ch->first();

            if ($check == null) {
                StockRequest::create([
                    'seeker_branch_id' => $user->access->branch_id,
                    'seeker_user_id' => $user->id,
                    'provider_branch_id' => $branch['id'],
                    'provider_user_id' => null,

                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'total_price' => $totalPrice,
                    'accepted_quantity' => $item['quantity'],
                    'accepted_total_price' => $totalPrice,
                    'is_accepted' => null,
                ]);
            } else {
                $newQuantity = $check->quantity + $item['quantity'];
                $newTotalPrice = $item['price'] * $newQuantity;
                $ch->update([
                    'quantity' => $newQuantity,
                    'total_price' => $newTotalPrice,
                ]);
            }
        }

        return response()->json(['ok']);
    }
    public function stockRequestReject($requestID, Request $request) {
        $data = StockRequest::where('id', $requestID);
        $stock = $data->first();

        $data->update([
            'is_accepted' => false,
        ]);
        
        return response()->json(['ok']);
    }
    public function stockRequestAccept(Request $request) {
        $user = me($request->user('user'));
        $ids = $request->ids;
        $inv = new InventoryController();
        $records = StockRequest::whereIn('id', $ids)
        ->with(['seeker_branch', 'provider_branch'])
        ->get();

        // group by seeker_branch_id
        $stockRequests = $records->groupBy('seeker_branch_id')->map(function ($items, $groupId) {
            return [
                'seeker_branch_id' => $groupId,
                'seeker_branch' => $items->first()->seeker_branch,
                'provider_branch' => $items->first()->provider_branch,
                'records'  => $items, // collection of StockRequest models
            ];
        })->values();

        // Create StockMovement Out
        foreach ($stockRequests as $req) {
            $movement = StockMovement::create([
                'user_id' => $user->id,
                'branch_id' => $req['records'][0]->provider_branch_id,
                'branch_id_destination' => $req['seeker_branch_id'],
                'type' => "outbound",
                'label' => "OUT".date('YmdHis'),
                'notes' => "Permintaan dari Cabang " . $req['seeker_branch']->name,
                'status' => "PUBLISHED"
            ]);

            $totalPrice = 0;
            $totalQuantity = 0;

            foreach ($req['records'] as $rec) {
                StockMovementProduct::create([
                    'movement_id' => $movement->id,
                    'product_id' => $rec->product_id,
                    'price' => $rec->total_price / $rec->quantity,
                    'quantity' => $rec->quantity,
                    'total_price' => $rec->total_price,
                ]);

                $totalPrice += $rec->total_price;
                $totalQuantity += $rec->quantity;
            }

            $movement->update([
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
            ]);

            $inv->proceed($movement->id, false, $user);
        }

        StockRequest::whereIn('id', $ids)->update([
            'is_accepted' => true,
        ]);

        return response()->json(['ok']);
    }
}
