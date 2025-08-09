<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MovementDetailExport implements FromView, ShouldAutoSize
{
    protected $product;
    protected $movements;
    protected $startDate;
    protected $endDate;

    public function __construct($props)
    {
        $this->product = $props['product'];
        $this->movements = $props['movements'];
        $this->startDate = $props['startDate'];
        $this->endDate = $props['endDate'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.movement_detail', [
            'product' => $this->product,
            'movements' => $this->movements,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
