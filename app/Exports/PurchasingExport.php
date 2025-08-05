<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchasingExport implements FromView, ShouldAutoSize
{
    protected $purchasings;

    public function __construct($props)
    {
        $this->purchasings = $props['purchasings'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.purchasing', [
            'purchasings' => $this->purchasings
        ]);
    }
}
