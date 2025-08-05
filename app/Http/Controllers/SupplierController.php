<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function store(Request $request) {
        $id = $request->id;
        $message = "";

        $payload = [
            'name' => $request->name,
            'pic_name' => $request->pic_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoFileName = rand(111111, 999999)."_".$photo->getClientOriginalName();
            $payload['photo'] = $photoFileName;
            $photo->move(
                public_path('storage/supplier_photos'),
                $photoFileName
            );
        }

        if ($id != "") {
            $sup = Supplier::where('id', $id);
            $supplier = $sup->first();
            $sup->update($payload);
            $message = "Berhasil mengubah data supplier " . $supplier->name;
        } else {
            $supplier = Supplier::create($payload);
            $message = "Berhasil menambahkan supplier " . $supplier->name;
        }

        return redirect()->route('supplier')->with([
            'message' => $message
        ]);
    }
    public function delete(Request $request) {
        $data = Supplier::where('id', $request->id);
        $supplier = $data->first();

        $deleteData = $data->delete();
        if ($supplier->photo != null) {
            Storage::delete('public/supplier_photos/' . $supplier->photo);
        }

        return redirect()->route('supplier')->with([
            'message' => "Berhasil menghapus supplier " . $supplier->name,
        ]);
    }
}
