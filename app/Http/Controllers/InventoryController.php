<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Purchasing;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use App\Models\StockRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{
    public function store(Request $request, $me = null, $returnValue = false) {
        $purchasingID = $request->purchasing_id;
        $purch = null;
        $purchasing = null;
        if ($me === null) {
            $me = me();
        }

        $toCreate = [
            'user_id' => $me->id,
            'branch_id' => $me->access->branch_id,
            'supplier_id' => $request->supplier_id,
            'branch_id_destination' => $request->branch_id_destination,
            'type' => $request->type,
            'notes' => $request->notes,
            'label' => $request->label,
            'total_quantity' => 0,
            'total_price' => 0,
            'status' => "DRAFT",
        ];

        if ($purchasingID != null) {
            $toCreate['purchasing_id'] = $purchasingID;
            $purch = Purchasing::where('id', $purchasingID);
            $purchasing = $purch->with(['items'])->first();
            $toCreate['supplier_'] = $purchasingID;
            $toCreate['purchasing_id'] = $purchasingID;
            $toCreate['supplier_id'] = $purchasing->supplier_id;
        }

        $inventory = StockMovement::create($toCreate);

        if ($purchasingID != null) {
            $purch->update([
                'inventory_id' => $inventory->id,
            ]);
            
            $newTotalPrice = 0;
            $newQuantity = 0;
            
            foreach ($purchasing->items as $item) {
                $newTotalPrice += $item->total_price;
                $newQuantity += $item->quantity;
                StockMovementProduct::create([
                    'movement_id' => $inventory->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ]);
            }

            $inventory->update([
                'total_quantity' => $newQuantity,
                'total_price' => $newTotalPrice,
            ]);
        }

        // $targetID = $request->target_branch_id;
        // if ($targetID != "") {
        //     $target = StockMovement::create([
        //         'type' => "inbound",
        //         'branch_id' => $targetID,
        //         'user_id' => $me->id,
        //         'label' => "IN".date('YmdHis'),
        //         'notes' => "Transfer Stok dari " . $inventory->branch->name,
        //         'status' => "DRAFT",
        //     ]);
        // }

        if ($returnValue) {
            return $inventory;
        } else {
            return redirect()->route('inventory.detail', $inventory->id);
        }
    }
    public function detail($id) {
        $message = Session::get('message');
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $inventory = StockMovement::where('id', $id)
        ->with(['items.product', 'supplier', 'origin'])
        ->first();

        $branches = [];
        
        if ($inventory->type == "outbound") {
            $me = me();
            $notIn = [$me->access->branch_id];
            if ($inventory->branch_id_destination != null) {
                array_push($notIn, $inventory->branch_id_destination);
            }
            $branches = Branch::whereNotIn('id', $notIn)
            ->orderBy('name', 'ASC')->get();
        }

        return view('user.inventory.detail', [
            'message' => $message,
            'suppliers' => $suppliers,
            'branches' => $branches,
            'inventory' => $inventory,
        ]);
    }
    public function proceed($id, $redirect = true, $me = null) {
        $inv = StockMovement::where('id', $id);
        $inventory = $inv->with(['branch', 'items.product'])->first();

        foreach ($inventory->items as $item) {
            $newQuantity = $item->product->quantity;
            if ($inventory->type == "inbound") {
                $newQuantity = $item->product->quantity + $item->quantity;
            } else if ($inventory->type == "outbound") {
                $newQuantity = $item->product->quantity - $item->quantity;
            } else if ($inventory->type == "opname") {
                $newQuantity = $item->quantity;
            }

            Product::where('id', $item->product_id)->update([
                'quantity' => $newQuantity,
            ]);
        }

        $inv->update([
            'status' => "PUBLISHED"
        ]);

        $message = "Berhasil mempublikasikan pergerakan stok";

        if ($inventory->type == "outbound" && $inventory->branch_id_destination != null) {
            if ($me == null) {
                $me = me();
            }
            $targetInventory = StockMovement::create([
                'branch_id' => $inventory->branch_id_destination,
                'user_id' => $me->id,
                'movement_id_ref' => $inventory->id,
                'type' => "inbound",
                'label' => "IN".date('YmdHis'),
                'notes' => "Penerimaan dari cabang " . $inventory->branch->name,
                'status' => "PUBLISH"
            ]);
            $targetTotalQuantity = 0;
            $targetTotalPrice = 0;

            foreach ($inventory->items as $item) {
                $product = Product::where([
                    ['name', 'LIKE', "%".$item->product->name."%"],
                    ['branch_id', $inventory->branch_id_destination],
                ])->first();

                if ($product == null) {
                    $product = Product::create([
                        'branch_id' => $inventory->branch_id_destination,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'description' => $item->product->description,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                    ]);

                    foreach ($item->product->images as $image) {
                        $newFileName = rand(1111, 9999).$image->filename;
                        ProductImage::create([
                            'product_id' => $product->id,
                            'filename' => $newFileName,
                            'size' => $image->size,
                            'caption' => $image->caption,
                        ]);

                        copy(
                            public_path('storage/product_images') . "/" . $image->filename,
                            public_path('storage/product_images') . "/" . $newFileName,
                        );
                    }

                    foreach ($item->product->categories as $category) {
                        ProductCategory::create([
                            'product_id' => $product->id,
                            'category_id' => $category->id,
                        ]);
                    }
                } else {
                    $product->increment('quantity', $item->quantity);
                }
                
                StockMovementProduct::create([
                    'movement_id' => $targetInventory->id,
                    'product_id' => $product->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ]);

                $targetTotalQuantity += $item->quantity;
                $targetTotalPrice += $item->total_price;
            }

            $targetInventory->update([
                'total_quantity' => $targetTotalQuantity,
                'total_price' => $targetTotalPrice,
            ]);

            $message .= " dan mencatat stok masuk di cabang " . $inventory->branch_destination->name;
        }

        if ($redirect) {
            return redirect()->route('inventory.detail', $id)->with([
                'message' => $message,
            ]);
        }
    }
    public function updateNotes($id, Request $request) {
        $data = StockMovement::where('id', $id);
        $data->update([
            'notes' => $request->notes,
        ]);

        return redirect()->route('inventory.detail', $id)->with([
            'message' => "Berhasil mengubah catatan"
        ]);
    }
    public function updateSupplier($id, Request $request) {
        $data = StockMovement::where('id', $id);
        $data->update([
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('inventory.detail', $id)->with([
            'message' => "Berhasil mengganti supplier"
        ]);
    }
    public function updateBranchDestination($id, Request $request) {
        $data = StockMovement::where('id', $id);
        $data->update([
            'branch_id_destination' => $request->branch_id_destination,
        ]);

        return redirect()->route('inventory.detail', $id)->with([
            'message' => "Berhasil mengganti cabang tujuan"
        ]);
    }
    public function removeProduct($id, $itemID, $fromApi = false) {
        $data = StockMovementProduct::where('id', $itemID);
        $item = $data->with(['product'])->first();

        $data->delete();

        $this->syncData($id);

        if (!$fromApi) {
            return redirect()->route('inventory.detail', $id)->with([
                'message' => "Berhasil menghapus produk " . $item->product->name,
            ]);
        }
    }
    public function updateQuantity($id, Request $request) {
        $query = StockMovementProduct::where('id', $request->item_id);
        $item = $query->with(['product'])->first();

        $quantity = $request->quantity;
        $newTotalPrice = $quantity * $item->product->price;
        $query->update([
            'quantity' => $quantity,
            'total_price' => $newTotalPrice,
        ]);

        $this->syncData($id);

        return redirect()->route('inventory.detail', $id)->with([
            'message' => "Berhasil mengubah jumlah produk"
        ]);
    }
    public function syncData($id) {
        // Calculating new data
        $data = StockMovement::where('id', $id);
        $inventory = $data->with(['items'])->first();
        $newQuantity = 0;
        $newTotalPrice = 0;
        foreach ($inventory->items as $item) {
            $newQuantity += $item->quantity;
            $newTotalPrice += $item->total_price;
        }
        $data->update([
            'total_quantity' => $newQuantity,
            'total_price' => $newTotalPrice,
        ]);
    }
    public function addProduct($id, Request $request) {
        $data = StockMovement::where('id', $id);
        $inventory = $data->first();
        $productIDs = json_decode($request->product_ids);
        $quantity = $request->quantity;

        foreach ($productIDs as $productID) {
            $product = Product::where('id', $productID)->first();
            $c = StockMovementProduct::where([
                ['movement_id', $id],
                ['product_id', $productID]
            ]);
            $check = $c->first();

            if ($check == null) {
                $item = StockMovementProduct::create([
                    'movement_id' => $id,
                    'product_id' => $productID,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total_price' => $product->price * $quantity,
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

        return redirect()->route('inventory.detail', $id)->with([
            'message' => "Berhasil menambahkan produk"
        ]);
    }

    public function stockRequestReject($requestID, Request $request) {
        $data = StockRequest::where('id', $requestID);
        $stock = $data->first();

        $data->update([
            'is_accepted' => false,
        ]);
        
        return redirect()->back()->with([
            'message' => "Permintaan ditolak"
        ]);
    }
    public function stockRequestStore(Request $request) {
        $user = me($request->user('user'));
        $productIDs = json_decode($request->product_ids);

        foreach ($productIDs as $productID) {
            $item = Product::where('id', $productID)->first();
            $totalPrice = $request->quantity * $item['price'];
            $ch = StockRequest::where([
                ['is_accepted', null],
                ['product_id', $item->id],
                ['seeker_branch_id', $user->access->branch_id],
                ['provider_branch_id', $request->branch_id]
            ]);
            $check = $ch->with(['product'])->first();

            if ($check == null) {
                StockRequest::create([
                    'seeker_branch_id' => $user->access->branch_id,
                    'seeker_user_id' => $user->id,
                    'provider_branch_id' => $request->branch_id,
                    'provider_user_id' => null,

                    'product_id' => $item->id,
                    'quantity' => $request->quantity,
                    'total_price' => $totalPrice,
                    'accepted_quantity' => $request->quantity,
                    'accepted_total_price' => $totalPrice,
                    'is_accepted' => null,
                ]);
            } else {
                $newQuantity = $check->quantity + $request->quantity;
                $newTotalPrice = $item->price * $newQuantity;
                $ch->update([
                    'quantity' => $newQuantity,
                    'total_price' => $newTotalPrice,
                ]);
            }
        }

        return redirect()->back()->with([
            'message' => "Berhasil mengirim permintaan"
        ]);
    }
}
