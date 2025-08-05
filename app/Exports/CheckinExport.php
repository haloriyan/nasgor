<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CheckinExport implements FromView, ShouldAutoSize
{
    protected $checkins;

    public function __construct($props)
    {
        $this->checkins = $props['checkins'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel.checkins', [
            'checkins' => $this->checkins
        ]);
    }
}
