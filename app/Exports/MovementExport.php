<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MovementExport implements FromView, ShouldAutoSize
{
    protected $products;
    protected $startDate;
    protected $endDate;

    public function __construct($props)
    {
        $this->products = $props['products'];
        $this->startDate = $props['startDate'];
        $this->endDate = $props['endDate'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.movement', [
            'products' => $this->products,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
