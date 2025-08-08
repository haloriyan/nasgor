<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class ProductSheetImport implements ToModel, WithHeadingRow
{
    protected $sheetName;
    protected $category;
    protected $me;

    public function __construct($sheetName, $category, $me)
    {
        $this->sheetName = $sheetName;
        $this->category = $category;
        $this->me = $me;
    }
    
    public function model(array $row)
    {
        // Product::where

        $product = Product::create([
            'branch_id' => $this->me->access->branch_id,
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'price' => $row['price'],
            'description' => "-",
        ]);

        ProductCategory::create([
            'category_id' => $this->category->id,
            'product_id' => $product->id,
        ]);
    }
}
