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

        foreach ($productIDs as $id) {
            $pivot = ProductAddOn::create([
                'product_id' => $id,
                'addon_id' => $addOn->id,
            ]);
        }

        return redirect()->route('product', ['tab' => "addon"])->with([
            'message' => "Berhasil membuat Add On"
        ]);
    }
}
