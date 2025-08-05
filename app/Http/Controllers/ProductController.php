<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAddOn;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use App\Models\ProductPrice;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store(Request $request) {
        $me = me();
        $categoryIDs = json_decode($request->category_ids);
        $images = $request->file('images');

        $product = Product::create([
            'branch_id' => $me->access->branch_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
        ]);

        foreach ($images as $image) {
            $imageFileName = rand(111111, 999999)."_".$image->getClientOriginalName();
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'filename' => $imageFileName,
                'size' => $image->getSize(),
            ]);
            $image->move(
                public_path('storage/product_images'),
                $imageFileName,
            );
        }

        if ($categoryIDs) {
            foreach ($categoryIDs as $cat) {
                $category = ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $cat,
                ]);
            }
        }

        return redirect()->route('product', [
            'tab' => "produk"
        ])->with([
            'message' => "Berhasil menambahkan produk " . $product->name,
        ]);
    }
    public function detail($id, Request $request) {
        $message = Session::get('message');
        
        $product = Product::where('id', $id)
        ->with(['images', 'prices', 'addons.addon', 'ingredients', 'categories'])
        ->first();

        $categories = Category::whereNotIn('id', $product->categories->pluck('id'))->orderBy('name', 'ASC')->get();
        $addOns = AddOn::whereNotIn('id', $product->addons->pluck('addon_id'))->get();
        
        return view('user.product.detail', [
            'message' => $message,
            'product' => $product,
            'categories' => $categories,
            'addOns' => $addOns,
        ]);
    }
    public function delete(Request $request) {
        $data = Product::where('id', $request->id);
        $product = $data->with(['images'])->first();

        $deleteData = $data->delete();
        foreach ($product->images as $image) {
            $deleteImage = Storage::delete('public/product_images/' . $image->filename);
        }

        return redirect()->route('product', ['tab' => "produk"])->with([
            'message' => "Berhasil menghapus produk " . $product->name,
        ]);
    }
    public function storeImage($id, Request $request) {
        $file = $request->file('image');
        $fileName = rand(111111, 999999) . "_" . $file->getClientOriginalName();

        $image = ProductImage::create([
            'product_id' => $id,
            'filename' => $fileName,
            'size' => $file->getSize(),
        ]);

        $file->move(
            public_path('storage/product_images'),
            $fileName,
        );

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menambahkan gambar"
        ]);
    }
    public function deleteImage($id, $imageID) {
        $img = ProductImage::where('id', $imageID);
        $image = $img->first();

        $img->delete();
        Storage::delete('public/product_images/' . $image->filename);

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menghapus gambar"
        ]);
    }
    public function updateInfo($id, Request $request) {
        $data = Product::where('id', $id);
        $data->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil mengubah informasi produk"
        ]);
    }
    public function addPrice($id, Request $request) {
        $price = ProductPrice::create([
            'product_id' => $id,
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menambahkan harga " . $price->label,
        ]);
    }
    public function removePrice($id, $priceID) {
        $pr = ProductPrice::where('id', $priceID);
        $price = $pr->first();

        $pr->delete();

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menghapus harga " . $price->label,
        ]);
    }
    public function removeAddOn($productID, $addOnID) {
        $query = ProductAddOn::where([
            ['product_id', $productID],
            ['addon_id', $addOnID]
        ]);

        $data = $query->with(['product'])->first();

        $deleteData = $query->delete();

        return redirect()->back()->with([
            'message' => "Berhasil menghapus add-on dari produk " . $data->product->name,
        ]);
    }
    public function toggleCategory($id, Request $request, $categoryID = null) {
        if ($categoryID == null) {
            $categoryID = $request->category_id;
        }
        $cat = ProductCategory::where([
            ['product_id', $id],
            ['category_id', $categoryID]
        ]);
        $category = $cat->first();
        $message = "";

        if ($category == null) {
            ProductCategory::create([
                'product_id' => $id,
                'category_id' => $categoryID,
            ]);

            $message = "Berhasil menambahkan kategori";
        } else {
            $cat->delete();
            $message = "Berhasil menghapus kategori";
        }

        return redirect()->route('product.detail', $id)->with([
            'message' => $message,
        ]);
    }
    public function storeIngredient($id, Request $request) {
        $ingredient = ProductIngredient::create([
            'product_id' => $id,
            'ingredient_id' => json_decode($request->ingredient_id)[0],
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menambahkan bumbu "
        ]);
    }
    public function deleteIngredient($id, $pivotID) {
        ProductIngredient::where('id', $pivotID)->delete();
        
        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menghapus resep"
        ]);
    }
    public function storeAddOn($id, Request $request) {
        $addOnIDs = json_decode($request->addon_ids);
        foreach ($addOnIDs as $addOnID) {
            $pivot = ProductAddOn::create([
                'product_id' => $id,
                'addon_id' => $addOnID,
            ]);
        }

        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menambahkan Add On"
        ]);
    }
    public function deleteAddOn($id, $pivotID) {
        ProductAddOn::where('id', $pivotID)->delete();
        
        return redirect()->route('product.detail', $id)->with([
            'message' => "Berhasil menghapus Add On"
        ]);
    }
}
