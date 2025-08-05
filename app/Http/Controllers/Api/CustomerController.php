<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function search(Request $request) {
        $filter = [['name', 'LIKE', '%'.$request->q.'%']];
        if ($request->branch_id != "") {
            array_push($filter, ['branch_id', $request->branch_id]);
        }
        $customers = Customer::where($filter)
        ->with(['types'])->take(25)->get();

        return response()->json([
            'customers' => $customers,
        ]);
    }
    public function store(Request $request) {
        $user = me($request->user('user'));
        Log::info($user);
        $message = "Berhasil menambahkan customer";

        $payload = [
            'branch_id' => $user->access->branch_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        $customer = Customer::create($payload);

        return response()->json([
            'message' => $message,
            'customer' => $customer,
        ]);
    }
}
