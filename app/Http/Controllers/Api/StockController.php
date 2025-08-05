<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryController;
use App\Models\StockMovement;
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
        $data = StockMovement::where('id', $id);
        $data->update([
            'status' => "PUBLISHED"
        ]);
        return response()->json([
            'message' => "Berhasil mempublikasikan"
        ]);
    }
}
