<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\ProductAddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    public function store(Request $request) {
        $productIDs = json_decode($request->product_ids);
        $me = me();

        $addOn = AddOn::create([
            'branch_id' => $me->access->branch_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($productIDs) {
            foreach (@$productIDs as $id) {
                $pivot = ProductAddOn::create([
                    'product_id' => $id,
                    'addon_id' => $addOn->id,
                ]);
            }
        }

        return redirect()->route('product', ['tab' => "addon"])->with([
            'message' => "Berhasil membuat Add On"
        ]);
    }
    public function update(Request $request) {
        $data = AddOn::where('id', $request->id);
        $data->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);
        
        return redirect()->back()->with([
            'message' => "Berhasil mengubah Add On"
        ]);
    }
    public function delete($id) {
        $data = AddOn::where('id', $id);
        $data->delete();
        
        return redirect()->back()->with([
            'message' => "Berhasil menghapus Add On"
        ]);
    }
}
