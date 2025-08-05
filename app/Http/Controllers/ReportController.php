<?php

namespace App\Http\Controllers;

use App\Exports\PurchasingExport;
use App\Exports\SalesExport;
use App\Models\Product;
use App\Models\Purchasing;
use App\Models\Sales;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function purchasingReport(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
            }
        }

        $purch = Purchasing::whereIn('branch_id', $myBranchIDs)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['branch', 'items.product', 'creator', 'receiver', 'supplier'])
        ->orderBy('created_at', 'DESC');

        $purchasings = [];
        if ($isDownloading) {
            $purchasings = $purch->get();
        } else {
            $purchasings = $purch->paginate(50);
        }

        if ($isDownloading) {
            $filename = "Purchasing_Report-Exported_at_" . Carbon::now()->isoFormat('DD-MMM-Y') . ".xlsx";

            return Excel::download(
                new PurchasingExport([
                    'purchasings' => $purchasings
                ]),
                $filename
            );
        }

        return view('user.report.purchasing', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'purchasings' => $purchasings,
        ]);
    }
    public function salesReport(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
            }
        }

        $sale = Sales::whereIn('branch_id', $myBranchIDs)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['items', 'branch', 'customer', 'user'])
        ->orderBy('created_at', 'DESC');

        $sales = [];
        if ($isDownloading) {
            $sales = $sale->get();
        } else {
            $sales = $sale->paginate(50);
        }

        if ($isDownloading) {
            $filename = "Sales_Report-Exported_at_" . Carbon::now()->isoFormat('DD-MMM-Y') . ".xlsx";

            return Excel::download(
                new SalesExport([
                    'sales' => $sales
                ]),
                $filename
            );
        }

        return view('user.report.sales', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sales' => $sales,
        ]);
    }
    public function stockMovement(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $myBranches = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }

        $query = new Product();
        $productsRaw = null;
        if ($request->branch_id != "") {
            $productsRaw = $query->where('branch_id', $request->branch_id);
        } else {
            $productsRaw = $query->whereIn('branch_id', $myBranchIDs);
        }
        $productsRaw = $productsRaw->with(['branch', 'images'])
        ->orderBy('updated_at', 'DESC')->paginate(25);

        $products = $productsRaw->items();

        foreach ($products as $p => $product) {
            $movements = [
                'inbound' => 0,
                'outbound' => 0,
                'opname' => 0,
            ];

            $stocks = StockMovementProduct::where('product_id', $product->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['movement'])->get();

            foreach ($stocks as $stock) {
                $movements[$stock->movement->type] = $stock->quantity;
            }

            $products[$p]->movements = $movements;
        }

        return view('user.report.movement', [
            'request' => $request,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'products' => $products,
            'branches' => $myBranches,
        ]);
    }
    public function stockMovementDetail($productID, Request $request) {
        $me = me();
        $myBranches = [];
        $myBranchIDs = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d H:i:s');

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }
        
        $myBranches = collect($myBranches);
        $movements = [];
        $product = Product::where('id', $productID)->first();
        $quantity = $product->quantity;
            
        $stocks = StockMovementProduct::where('product_id', $product->id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['movement'])
        ->orderBy('created_at', 'DESC')->get();

        $inboundSeries = [
            'label' => [],
            'data' => []
        ];
        $outboundSeries = [
            'label' => [],
            'data' => []
        ];

        foreach ($stocks as $stock) {
            if ($stock->movement->type == "inbound") {
                $quantity = $quantity - $stock->quantity;
                array_push($inboundSeries['data'], $stock->quantity);
                array_push($inboundSeries['label'], Carbon::parse($stock->created_at)->isoFormat('DD MMM, HH:mm'));
            } else if ($stock->movement->type == "outbound") {
                $quantity = $quantity + $stock->quantity;
                array_push($outboundSeries['data'], $stock->quantity);
                array_push($outboundSeries['label'], Carbon::parse($stock->created_at)->isoFormat('DD MMM, HH:mm'));
            } else if ($stock->movement->type == "opname") {
                $quantity = $stock->quantity;
            }

            array_push($movements, [
                'quantity' => $quantity,
                'date' => $stock->created_at,
                'movement_amount' => $stock->quantity,
                'type' => $stock->movement->type,
            ]);
        }

        $movements = array_reverse($movements);
        $product->movements = $movements;

        $movementSeries = [];
        foreach ($movements as $m => $move) {
            $open = $m > 0 ? $movements[$m - 1]['quantity'] : $move['quantity']; // fallback to self if first

            $close = $move['quantity'];
            $low = min($open, $close);
            $high = max($open, $close);

            $movementSeries[] = [$open, $close, $low, $high];
        }

        $MovementChartConfig = [
            'title' => ['text' => "Pergerakan Stok"],
            'tooltip' => ['trigger' => 'axis'],
            'grid' => [
                'left' => '1%',
                'right' => '2%',
                'bottom' => '0%',
                'containLabel' => true,
            ],
            'toolbox' => ['feature' => ['saveAsImage' => []]],
            'legend' => ['data' => ['Stock Quantity']],
            'xAxis' => [
                'data' => collect($movements)->map(fn($m) => Carbon::parse($m['date'])->isoFormat('DD MMM, HH:mm'))->toArray(),
            ],
            'yAxis' => ['type' => "value"],
            'series' => [
                [
                    'type' => "candlestick",
                    'data' => $movementSeries,
                    'itemStyle' => [
                        'color' => "#22C55E",
                        'color0' => "#EF4444",
                        'borderColor' => "#22C55E",
                        'borderColor0' => "#EF4444",
                    ]
                ]
            ]
        ];

        $chartOptions = [
            'tooltip' => ['trigger' => "axis"],
            'grid' => [
                'left' => "1%",
                'right' => "2%",
                'bottom' => "0%",
                'containLabel' => true,
            ],
            'toolbox' => [
                'feature' => ['saveAsImage' => []]
            ],
            'yAxis' => ['type' => "value"],
        ];

        $inboundSeries = json_decode(json_encode($inboundSeries), false);
        $outboundSeries = json_decode(json_encode($outboundSeries), false);

        $InboundChartConfig = array_merge($chartOptions, [
            'title' => ['text' => "Stok Masuk"],
            'xAxis' => [
                'type' => "category",
                'data' => array_reverse($inboundSeries->label),
            ],
            'series' => [
                [
                    'data' => array_reverse($inboundSeries->data),
                    'type' => "bar",
                    'itemStyle' => [
                        'color' => "#22C55E"
                    ]
                ]
            ]
        ]);
        $OutboundChartConfig = array_merge($chartOptions, [
            'title' => ['text' => "Stok Keluar"],
            'xAxis' => [
                'type' => "category",
                'data' => array_reverse($outboundSeries->label),
            ],
            'series' => [
                [
                    'data' => array_reverse($outboundSeries->data),
                    'type' => "bar",
                    'itemStyle' => [
                        'color' => "#EF4444"
                    ]
                ]
            ]
        ]);
        
        return view('user.report.movement_detail', [
            'product' => $product,
            'movements' => $movements,
            'MovementChartConfig' => $MovementChartConfig,
            'InboundChartConfig' => $InboundChartConfig,
            'OutboundChartConfig' => $OutboundChartConfig,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
