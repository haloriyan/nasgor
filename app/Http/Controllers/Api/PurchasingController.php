<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PurchasingController as ControllersPurchasingController;
use App\Models\Purchasing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchasingController extends Controller
{
    public function store(Request $request) {
        $user = $request->user('user');
        $user = me($user);

        $purchase = Purchasing::create([
            'branch_id' => $user->access->branch_id,
            'supplier_id' => $request->supplier_id,
            'label' => $request->label,
            'notes' => $request->notes,
            'status' => "DRAFT",
            'created_by' => $user->id,
        ]);

        $purchasing = Purchasing::where('id', $purchase->id)
        ->with(['items.product.images', 'branch', 'supplier', 'staff', 'receiver'])
        ->first();

        return response()->json([
            'purchasing' => $purchasing,
        ]);
    }
    public function detail($id) {
        $purchasing = Purchasing::where('id', $id)
        ->with(['items.product.images', 'branch', 'supplier', 'staff', 'receiver'])
        ->first();

        return response()->json([
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
    public function update($id, Request $request) {
        $data = Purchasing::where('id', $id);
        $data->update($request->all());

        return redirect()->route('purchasing.detail', $id)->with([
            'message' => "Berhasil menyimpan perubahan"
        ]);
    }
    public function addProduct($id, Request $request) {
        $controller = new ControllersPurchasingController();
        $controller->addProduct($id, $request);

        return response()->json([
            'message' => "Berhasil menambah produk"
        ]);
    }
    public function removeProduct($id, $itemID, Request $request) {
        $controller = new ControllersPurchasingController();
        $controller->removeProduct($id, $itemID);

        return response()->json([
            'message' => "Berhasil menghapus produk"
        ]);
    }
    public function publish($id, Request $request) {
        $user = me($request->user('user'));
        $controller = new ControllersPurchasingController();
        $controller->receive($id, $request, $user, true);

        return response()->json([
            'message' => "Berhasil menambah produk"
        ]);
    }
}
