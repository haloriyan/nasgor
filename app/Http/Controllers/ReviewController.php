<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Sales;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store($invoiceNumber, Request $request) {
        $sales = Sales::where('invoice_number', $invoiceNumber)->with(['customer'])->first();
        $review = Review::create([
            'sales_id' => $sales->id,
            'customer_id' => $sales->customer_id,
            'branch_id' => $sales->branch_id,
            'rating' => $request->rating,
            'body' => $request->body,
        ]);

        return redirect()->route('invoice', $invoiceNumber)->with([
            'message' => "Berhasil menulis ulasan"
        ]);
    }
}
