<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCustomerType;
use App\Models\CustomerType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function storeType(Request $request) {
        $me = me();
        $id = $request->id;
        $message = "";

        $payload = [
            'name' => $request->name,
            'color' => $request->color,
        ];

        if ($id != "") {
            $sup = CustomerType::where('id', $id);
            $type = $sup->first();
            $sup->update($payload);
            $message = "Berhasil mengubah tipe customer " . $type->name;
        } else {
            $payload['branch_id'] = $me->access->branch_id;
            $type = CustomerType::create($payload);
            $message = "Berhasil menambahkan tipe customer " . $type->name;
        }

        return redirect()->route('customer', ['tab' => 'customer_type'])->with([
            'message' => $message
        ]);
    }
    public function removeCustomerFromType($typeID, $customerID) {
        $query = CustomerCustomerType::where([
            'customer_type_id' => $typeID,
            'customer_id' => $customerID
        ]);
        $data = $query->with(['type', 'customer'])->first();
        $query->delete();

        return redirect()->route('customer', [
            'tab' => 'customer_type_detail',
            'type_id' => $typeID,
        ])->with([
            'message' => "Berhasil menghapus " . $data->customer->name . " dari " . $data->type->name,
        ]);
    }
    public function addCustomerToType($typeID, Request $request) {
        $customerIDs = json_decode($request->customer_ids);
        $type = CustomerType::where('id', $typeID)->first();

        foreach ($customerIDs as $id) {
            $check = CustomerCustomerType::where([
                ['customer_type_id', $typeID],
                ['customer_id', $id]
            ])->first();
            
            if ($check == null) {
                CustomerCustomerType::create([
                    'customer_type_id' => $typeID,
                    'customer_id' => $id,
                ]);
            }
        }

        return redirect()->route('customer', [
            'tab' => 'customer_type_detail',
            'type_id' => $typeID,
        ])->with([
            'message' => "Berhasil menambahkan pelanggan ke " . $type->name,
        ]);
    }
    public function deleteType(Request $request) {
        $query = CustomerType::where('id', $request->id);
        $type = $query->first();

        $deleteData = $query->delete();

        return redirect()->route('customer', [
            'tab' => 'customer_type',
        ])->with([
            'message' => "Berhasil menghapus pelanggan ke " . $type->name,
        ]);
    }
    public function store(Request $request) {
        $me = me();
        $id = $request->id;
        $message = "";
        $typeIDs = json_decode($request->type_ids);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($id != "") {
            $sup = CustomerType::where('id', $id);
            $type = $sup->first();
            $sup->update($payload);
            $message = "Berhasil mengubah tipe customer " . $type->name;
        } else {
            $payload['branch_id'] = $me->access->branch_id;
            $customer = Customer::create($payload);
            foreach ($typeIDs as $typeID) {
                CustomerCustomerType::create([
                    'customer_id' => $customer->id,
                    'customer_type_id' => $typeID
                ]);
            }
            $message = "Berhasil menambahkan tipe customer " . $customer->name;
        }

        return redirect()->route('customer', ['tab' => 'customer'])->with([
            'message' => $message
        ]);
    }
    public function delete($id) {
        $data = Customer::where('id', $id);
        $customer = $data->first();

        $data->delete();

        return redirect()->route('customer', ['tab' => 'customer'])->with([
            'message' => "Berhasil menghapus " . $customer->name,
        ]);
    }
}
