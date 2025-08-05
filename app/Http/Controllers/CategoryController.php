<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function store(Request $request) {
        $toCreate = [
            'name' => $request->name,
            'is_active' => true,
            'pos_visibility' => true,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageFileName = rand(111111, 999999)."_".$image->getClientOriginalName();
            $toCreate['image'] = $imageFileName;
            $image->move(
                public_path('storage/category_images'),
                $imageFileName
            );
        }

        if ($request->id == null) {
            $category = Category::create($toCreate);
            $message = "Kategori " . $category->name . " berhasil ditambahkan";
        } else {
            $category = Category::where('id', $request->id)->update($toCreate);
            $message = "Kategori " . $category->name . " berhasil diubah";
        }

        return redirect()->route('product', [
            'tab' => "kategori"
        ])->with([
            'message' => $message,
        ]);
    }
    public function delete(Request $request) {
        $data = Category::where('id', $request->id);
        $category = $data->first();

        $deleteData = $data->delete();
        if ($category->image != null) {
            Storage::delete('public/category_images/' . $category->image);
        }
        
        return redirect()->route('product', [
            'tab' => "kategori"
        ])->with([
            'message' => "Kategori " . $category->name . " berhasil dihapus"
        ]);
    }
    public function togglePos($id) {
        $data = Category::where('id', $id);
        $category = $data->first();

        $data->update([
            'pos_visibility' => !$category->pos_visibility
        ]);

        return redirect()->route('product', [
            'tab' => "kategori"
        ]);
    }
}
