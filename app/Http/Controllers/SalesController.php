<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\SalesItemAddon;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function store(Request $request) {
        $me = me();

        $sales = Sales::create([
            'user_id' => $me->id,
            'branch_id' => $me->access->branch_id,
            'customer_id' => json_decode($request->customer_id)[0],
            'invoice_number' => "INV_".date('YmdHis'),
            'status' => "DRAFT",
            'payment_status' => "UNPAID",
            'total_price' => 0,
            'total_quantity' => 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('sales.detail', $sales->id);
    }
    public function detail($id, Request $request) {
        $message = Session::get('message');
        $sales = Sales::where('id', $id)
        ->with(['items', 'items.product.images', 'items.addons.addon', 'items.price_data', 'customer', 'review', 'branch', 'user'])
        ->first();

        $waLink = null;
        if (@$sales->customer->phone != null) {
            $waLink = $this->buildWAMeLink($sales);
        }
        
        if (in_array('api', $request->route()->middleware())) {
            return response()->json([
                'sales' => $sales,
            ]);
        } else {
            return view('user.sales.detail.index', [
                'sales' => $sales,
                'message' => $message,
                'waLink' => $waLink,
            ]);
        }
    }

    public function buildWAMeLink($sale) {

        $invoiceNumber = $sale->invoice_number;
        $invoiceDate = \Carbon\Carbon::parse($sale->created_at)->isoFormat('D MMMM Y');
        $customerName = $sale->customer->name;
        $storeName = $sale->branch->name;
        $customerPhone = $sale->customer->phone;
        $totalPrice = $sale->total_price;

        $orderText = '';
        foreach ($sale->items as $item) {
            $productName = $item->product->name ?? "-";
            $qty = $item->quantity;
            $grandTotal = $item->grand_total;
            $orderText .= "- {$productName} x{$qty} — Rp" . number_format($grandTotal, 0, ',', '.') . "\n";

            if ($item->addons->isNotEmpty()) {
                foreach ($item->addons as $addonItem) {
                    $addonName = $addonItem->addon->name;
                    $addonQty = $addonItem->quantity;
                    $addonTotal = $addonItem->total_price;
                    $orderText .= "   • {$addonName} x{$addonQty} — Rp" . number_format($addonTotal, 0, ',', '.') . "\n";
                }
            }
        }

        $message = "Halo {$customerName}
        
Berikut adalah rincian pesanan Anda :

*Invoice No* : {$invoiceNumber}
*Tanggal* : {$invoiceDate}
*Nama* : {$customerName}
*Pesanan* :
{$orderText}
Total Pembayaran : " . currency_encode($totalPrice) . "

Terima kasih atas kepercayaannya\n
Salam,
_".env('APP_NAME')." - {$storeName}_

".route('invoice', $invoiceNumber);

        // Generate wa.me link
        $waPhone = preg_replace('/[^0-9]/', '', $customerPhone); // digits only
        $waPhone = preg_replace('/^0/', '62', $waPhone);         // local to intl format
        $waLink = 'https://wa.me/' . $waPhone . '?text=' . urlencode($message);

        return $waLink;

    }
    public function storeProduct($id, Request $request) {
        $addons = json_decode($request->addons) ?? [];
        $productPrice = ProductPrice::where('id', $request->product_price_id)->first();
        $toCreate = [
            'sales_id' => $id,
            'product_id' => $request->product_id,
            'price_id' => $request->product_price_id,
            'quantity' => $request->product_quantity,
            'price' => $productPrice->value,
            'total_price' => $request->product_quantity * $productPrice->value,
            'additional_price' => 0,
            'grand_total' => $request->product_quantity * $productPrice->value,
        ];

        $item = SalesItem::create($toCreate);

        foreach ($addons as $addon) {
            if ($addon->quantity > 0) {
                $addOn = AddOn::where('id', $addon->id)->first();
                SalesItemAddon::create([
                    'item_id' => $item->id,
                    'addon_id' => $addon->id,
                    'quantity' => $addon->quantity,
                    'price' => $addOn->price,
                    'total_price' => $addOn->price * $addon->quantity,
                ]);

                $toCreate['additional_price'] += $addOn->price * $addon->quantity;
            }
        }

        $item->update([
            'additional_price' => $toCreate['additional_price'],
            'grand_total' => $item->grand_total + $toCreate['additional_price']
        ]);

        $this->syncItems($id);

        return redirect()->route('sales.detail', $id)->with([
            'message' => "Berhasil menambahkan produk"
        ]);
    }
    public function deleteProduct($id, $pivotID) {
        $data = SalesItem::where('id', $pivotID);
        $item = $data->first();

        $data->delete();
        $this->syncItems($id);

        return redirect()->route('sales.detail', $id)->with([
            'message' => "Berhasil menghapus produk"
        ]);
    }
    public function syncItems($id) {
        $sale = Sales::where('id', $id);
        $sales = $sale->with(['items'])->first();

        $newTotalQuantitiy = 0;
        $newTotalPrice = 0;

        foreach ($sales->items as $item) {
            $newTotalQuantitiy += $item->quantity;
            $newTotalPrice += $item->grand_total;
        }

        $sale->update([
            'total_quantity' => $newTotalQuantitiy,
            'total_price' => $newTotalPrice,
        ]);
    }
    public function updateNotes($id, Request $request) {
        $data = Sales::where('id', $id);
        $data->update([
            'notes' => $request->notes,
        ]);

        return redirect()->route('sales.detail', $id)->with([
            'message' => "Berhasil mengubah catatan"
        ]);
    }
    public function updateCustomer($id, Request $request) {
        $data = Sales::where('id', $id);
        $data->update([
            'customer_id' => json_decode($request->customer_ids)[0],
        ]);

        return redirect()->route('sales.detail', $id)->with([
            'message' => "Berhasil mengubah catatan"
        ]);
    }

    public function proceed($id, $me = null) {
        if ($me == null) {
            $me = me();
        }
        $sale = Sales::where('id', $id);
        $sales = $sale->with(['items.product.ingredients.ingredient'])->first();
        Log::info($sales);
        $movementItems = [];

        foreach ($sales->items as $item) {
            $product = $item->product;
            array_push($movementItems, [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'total_price' => $product->price * $item->quantity,
            ]);

            foreach ($product->ingredients as $ingredient) {
                array_push($movementItems, [
                    'product_id' => $ingredient->ingredient->id,
                    'product_name' => $ingredient->ingredient->name,
                    'price' => $ingredient->ingredient->price,
                    'quantity' => $item->quantity * $ingredient->quantity,
                    'total_price' => $ingredient->ingredient->price * ($item->quantity * $ingredient->quantity),
                ]);
            }
        }

        Log::info($movementItems);

        if ($sales->status != "DRAFT") {
            return redirect()->back();
        }

        $movement = StockMovement::create([
            'user_id' => $me->id,
            'branch_id' => $me->access->branch_id,
            'sales_id' => $id,
            'label' => "OUT".date('YmdHis'),
            'type' => "outbound",
            "notes" => "Penjualan " . $sales->invoice_number,
            'status' => "PUBLISHED",
            'total_quantity' => $sales->total_quantity,
            'total_price' => $sales->total_price,
        ]);

        foreach ($movementItems as $item) {
            $item['movement_id'] = $movement->id;
            StockMovementProduct::create($item);
        }

        $inventoryController = new InventoryController();
        $inventoryController->proceed($movement->id, false);

        $sale->update([
            'status' => "PUBLISHED"
        ]);

        Log::info('DONE');

        return redirect()->route('sales.detail', $id);
    }
    public function togglePaymentStatus($id) {
        $data = Sales::where('id', $id);
        $sales = $data->first();
        $data->update([
            'payment_status' => $sales->payment_status == "PAID" ? "UNPAID" : "PAID"
        ]);

        return redirect()->route('sales.detail', $id)->with([
            'message' => "Berhasil mengubah status pembayaran"
        ]);
    }
    public function invoice($invoice_number) {
        $message = Session::get('message');
        $sales = Sales::where('invoice_number', $invoice_number)
        ->with(['items', 'customer', 'branch', 'review'])
        ->first();

        return view('invoice', [
            'sales' => $sales,
            'message' => $message,
        ]);
    }
    public function void($id, Request $request) {
        $data = Sales::where('id', $id);
        $sales = $data->first();

        $data->update([
            'status' => "VOID"
        ]);

        $restock = $request->restock;

        if ($restock) {
            $move = StockMovement::where('sales_id', $sales->id);
            $movement = $move->with(['items'])->first();

            foreach ($movement->items as $item) {
                Product::where('id', $item->product_id)->increment('quantity', $item->quantity);
            }
            
            $move->delete();
        }

        return $sales;
    }
}
