<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchasing;
use App\Models\PurchasingProduct;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PurchasingController extends Controller
{
    public function store(Request $request) {
        $me = me();
        $purchase = Purchasing::create([
            'branch_id' => $me->access->branch_id,
            'supplier_id' => $request->supplier_id,
            'label' => $request->label,
            'notes' => $request->notes,
            'status' => "DRAFT",
            'created_by' => $me->id,
        ]);

        return redirect()->route('purchasing.detail', $purchase->id);
    }
    public function detail($id) {
        $message = Session::get('message');
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $purchasing = Purchasing::where('id', $id)->with(['items.product'])->first();

        return view('user.purchasing.detail', [
            'message' => $message,
            'suppliers' => $suppliers,
            'purchasing' => $purchasing,
        ]);
    }
    public function updateNotes($id, Request $request) {
        $data = Purchasing::where('id', $id);
        $data->update([
            'notes' => $request->notes,
        ]);

        return redirect()->route('purchasing.detail', $id)->with([
            'message' => "Berhasil mengubah catatan"
        ]);
    }
    public function updateSupplier($id, Request $request) {
        $data = Purchasing::where('id', $id);
        $data->update([
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('purchasing.detail', $id)->with([
            'message' => "Berhasil mengganti supplier"
        ]);
    }
    public function updateQuantity($id, Request $request) {
        $query = PurchasingProduct::where('id', $request->item_id);
        $item = $query->with(['product'])->first();

        $quantity = $request->quantity;
        $price = $request->price;
        $newTotalPrice = $quantity * $price;
        $query->update([
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $newTotalPrice,
        ]);

        $this->syncData($id);

        return redirect()->route('purchasing.detail', $id)->with([
            'message' => "Berhasil mengubah jumlah produk"
        ]);
    }
    public function syncData($id) {
        // Calculating new data
        $data = Purchasing::where('id', $id);
        $purchasing = $data->with(['items'])->first();
        $newQuantity = 0;
        $newTotalPrice = 0;
        foreach ($purchasing->items as $item) {
            $newQuantity += $item->quantity;
            $newTotalPrice += $item->total_price;
        }
        $data->update([
            'total_quantity' => $newQuantity,
            'total_price' => $newTotalPrice,
        ]);
    }
    public function addProduct($id, Request $request, $fromApi = false) {
        $data = Purchasing::where('id', $id);
        $purchasing = $data->first();
        $productIDs = json_decode($request->product_ids);
        $quantity = $request->quantity;

        foreach ($productIDs as $productID) {
            $product = Product::where('id', $productID)->first();
            $c = PurchasingProduct::where([
                ['purchasing_id', $id],
                ['product_id', $productID]
            ]);
            $check = $c->first();
            $price = $request->price ?? $product->price;
            Log::info($request->price);

            if ($check == null) {
                $item = PurchasingProduct::create([
                    'purchasing_id' => $id,
                    'product_id' => $productID,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $price * $quantity,
                ]);
            } else {
                $newQuantity = $check->quantity;
                $newTotalPrice = $product->price * $newQuantity;
                $c->update([
                    'quantity' => $newQuantity,
                    'total_price' => $newTotalPrice,
                ]);
            }
        }

        $this->syncData($id);

        if (!$fromApi) {
            return redirect()->route('purchasing.detail', $id)->with([
                'message' => "Berhasil menambahkan produk"
            ]);
        }
    }
    public function removeProduct($id, $itemID) {
        $data = PurchasingProduct::where('id', $itemID);
        $item = $data->with(['product'])->first();

        $data->delete();

        $this->syncData($id);

        return redirect()->route('purchasing.detail', $id)->with([
            'message' => "Berhasil menghapus produk " . $item->product->name,
        ]);
    }
    public function receive($id, Request $request, $me = null, $returnValue = false) {
        if ($me === null) {
            $me = me();
        }
        $purch = Purchasing::where('id', $id);
        $purchasing = $purch->with(['items'])->first();

        $toUpdate = [
            'status' => "RECEIVED",
            'received_at' => date('Y-m-d H:i:s'),
            'recipient' => $me->id,
        ];

        if ($request->store_movement == 1) {
            // Create movement
            $movement = StockMovement::create([
                'user_id' => $me->id,
                'branch_id' => $purchasing->branch_id,
                'supplier_id' => $purchasing->supplier_id,
                'purchasing_id' => $purchasing->id,
                'type' => "inbound",
                'label' => "IN" . date('YmdHis'),
                'status' => "DRAFT",
                'notes' => "Stok Masuk dari Pembelian " . $purchasing->label,
            ]);

            foreach ($purchasing->items as $item) {
                $item = StockMovementProduct::create([
                    'movement_id' => $movement->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                ]);
            }
        }

        $purch->update($toUpdate);

        if ($returnValue) {
            return [
                'purchasing' => $purch->first(),
                'movement' => $movement,
            ];
        } else {
            if ($request->store_movement == 1) {
                return redirect()->route('inventory.detail', $movement->id);
            } else {
                return redirect()->route('purchasing.detail', $id)->with([
                    'message' => "Berhasil memproses pembelian"
                ]);
            }
        }
    }
}
