<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SalesController;
use App\Models\Category;
use App\Models\Customer;
use App\Models\ProductPrice;
use App\Models\Sales;
use App\Models\SalesItem;
use App\Models\SalesItemAddon;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    public function index(Request $request) {
        $user = me($request->user('user'));
        $branch = $user->access->branch;
        $categories = Category::where('pos_visibility', true)
        ->whereHas('products', function ($query) use ($branch) {
            $query->where('branch_id', $branch->id);
        })
        ->with(['products.images', 'products.prices', 'products.addons.addon'])
        ->orderBy('name', 'ASC')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }
    public function store(Request $request) {
        $me = me($request->user('user'));
        $items = json_decode(json_encode($request->items), false);

        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($items as $item) {
            $totalPrice += $item->price * $item->quantity;
            $totalQuantity += $item->quantity;
            foreach ($item->addons as $addon) {
                $totalPrice += $addon->total_price;
            }
        }
        
        $sales = Sales::create([
            'user_id' => $me->id,
            'branch_id' => $me->access->branch_id,
            'customer_id' => $request->customer_id,
            'invoice_number' => "INV_".date('YmdHis'),
            'status' => "PUBLISHED",
            'payment_status' => "PAID",
            'total_price' => $totalPrice,
            'total_quantity' => $totalQuantity,
            'notes' => $request->notes,
        ]);

        foreach ($items as $item) {
            $price = ProductPrice::where([
                ['product_id', $item->id],
                ['value', $item->price]
            ])->first();

            $salesItem = SalesItem::create([
                'sales_id' => $sales->id,
                'product_id' => $item->id,
                'price_id' => $price->id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total_price' => $item->price * $item->quantity,
                'additional_price' => $item->additional_price,
                'grand_total' => $item->grand_total,
            ]);

            foreach ($item->addons as $addon) {
                SalesItemAddon::create([
                    'item_id' => $salesItem->id,
                    'addon_id' => $addon->id,
                    'price' => $addon->price,
                    'quantity' => $addon->quantity,
                    'total_price' => $addon->total_price,
                ]);
            }
        }

        // Move stock
        $salesController = new SalesController();
        $salesController->proceed($sales->id);

        $sales = Sales::where('id', $sales->id)
        ->with(['items', 'items.product.images', 'items.addons.addon', 'customer', 'review', 'branch', 'user'])
        ->first();

        return response()->json([
            'message' => "ok",
            'sales' => $sales,
        ]);
    }
}
