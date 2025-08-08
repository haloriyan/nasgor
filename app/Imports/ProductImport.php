<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductImport implements WithMultipleSheets
{
    protected $sheetNames;
    protected $me;

    public function __construct($sheetNames = [], $me)
    {
        $this->sheetNames = $sheetNames;
        $this->me = $me;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheetNames as $sheetName) {
            $category = Category::where('name', 'LIKE', '%'.$sheetName.'%')->first();
            if ($category == null) {
                $category = Category::create([
                    'name' => $sheetName,
                    'priority' => 0,
                    'is_active' => true,
                    'pos_visibility' => true,
                ]);
            }

            $sheets[$sheetName] = new ProductSheetImport($sheetName, $category, $this->me);
        }

        return $sheets;
    }
}
