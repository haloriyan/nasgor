<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request) {
        $filter = [['name', 'LIKE', '%'.$request->q.'%']];
        if ($request->branch_id != "") {
            array_push($filter, ['branch_id', $request->branch_id]);
        }
        $products = Product::where($filter)
        ->with(['addons.addon', 'prices', 'images'])
        ->take(25)->get();

        return response()->json([
            'products' => $products,
        ]);
    }
}
