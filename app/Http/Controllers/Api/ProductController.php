<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function search(Request $request) {
        $limit = $request->limit ?? 25;
        $filter = [['name', 'LIKE', '%'.strtolower($request->q).'%']];
        if ($request->branch_id != "") {
            array_push($filter, ['branch_id', $request->branch_id]);
        }

        $query = Product::where($filter);

        if ($request->requestable == 1) {
            $limit = 999999;
            $query = $query->whereHas('categories', function ($q) {
                $q->where('requestable', true);
            });
        }

        $products = $query->with(['addons.addon', 'prices', 'images'])
        ->take($limit)->get();

        return response()->json([
            'products' => $products,
        ]);
    }
}
