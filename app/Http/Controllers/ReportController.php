<?php

namespace App\Http\Controllers;

use App\Exports\MovementDetailExport;
use App\Exports\MovementExport;
use App\Exports\PurchasingExport;
use App\Exports\SalesExport;
use App\Models\Branch;
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
    protected $userController;

    public function __construct()
    {
        $this->userController = new UserController();
    }
    public function purchasingReport(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $myBranches = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7);
        $endDate = $request->end_date ?? Carbon::now();
        $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }

        $purch = new Purchasing();
        if ($request->branch_id != "") {
            $purch = $purch->where('branch_id', $request->branch_id);
        } else {
            $purch = $purch->whereIn('branch_id', $myBranchIDs);
        }
        if ($request->q != "") {
            $purch = $purch->where('label', 'LIKE', '%'.$request->q.'%');
        }
        
        $purch = $purch->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])
        ->with(['branch', 'items.product', 'creator', 'receiver', 'supplier'])
        ->orderBy('created_at', 'DESC');

        $purchasings = [];
        if ($isDownloading) {
            $purchasings = $purch->get();
        } else {
            $purchasings = $purch->paginate(25)->withQueryString();
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
            'request' => $request,
            'branches' => $myBranches,
        ]);
    }
    public function salesReport(Request $request, $branchID = null) {
        $me = me();
        $myBranchIDs = [];
        $myBranches = [];
        $omsetSeries = [];
        $volumeSeries = [];
        $omset = 0;
        $volume = 0;
        if ($branchID == null) {
            $branchID = $request->branch_id;
        }

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                if ($branchID == null) {
                    array_push($myBranchIDs, $access->branch_id);
                    array_push($myBranches, $access->branch);
                }
            }
        }

        if ($branchID != null) {
            $theBranch = Branch::where('id', $branchID)->first();
            array_push($myBranches, $theBranch);
            array_push($myBranchIDs, $branchID);
        }
        $myBranches = collect($myBranches);

        $userController = new UserController();
        $ranges = $this->userController->generateDateRangeIndexes($request->date_range ?? 'today');
        $rawSales = [];

        foreach ($myBranches as $branch) {
            $theOmsetSeries = [
                'name' => $branch->name,
                'type' => "line",
                'data' => []
            ];
            $theVolumeSeries = [
                'name' => $branch->name,
                'type' => "line",
                'data' => []
            ];

            foreach ($ranges as $d => $date) {
                $dateBetween = [];
                $sale = Sales::where([
                    ['branch_id', $branch->id],
                    ['status', 'PUBLISHED'],
                    ['payment_status', 'PAID']
                ]);
                if ($date['start'] == $date['end']) {
                    $sale = $sale->where('created_at', 'LIKE', '%'.$date['start'].'%');
                } else {
                    $sale = $sale->whereBetween('created_at', [$date['start'], $date['end']]);
                }
                $sales = $sale->with(['items.product', 'branch'])->get();

                foreach ($sales as $item) {
                    array_push($rawSales, $item);
                }
                array_push($theOmsetSeries['data'], $sales->sum('total_price'));
                array_push($theVolumeSeries['data'], $sales->count());
                $omset += $sales->sum('total_price');
                $volume += $sales->count();
            }

            array_push($omsetSeries, $theOmsetSeries);
            array_push($volumeSeries, $theVolumeSeries);
        }

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
            'legend' => [
                'data' => $myBranches->pluck('name')
            ],
            'xAxis' => [
                'type' => "category",
                'boundaryGap' => false,
                'data' => collect($ranges)->map(function ($date) use ($request) {
                    if ($date['start'] == $date['end']) {
                        return Carbon::parse($date['start'])->isoFormat('DD MMM');
                    } else {
                        $format = "DD MMM";
                        if ($request->date_range == "today") {
                            $format = "HH:mm:ss";
                        }
                        return Carbon::parse($date['start'])->isoFormat($format) . " - " . Carbon::parse($date['end'])->isoFormat($format);
                    }
                }),
            ],
        ];

        $omsetChart = array_merge($chartOptions, [
            'series' => $omsetSeries,
        ]);
        $volumeChart = array_merge($chartOptions, [
            'series' => $volumeSeries,
        ]);

        $topProducts = collect($rawSales) // your raw data
        ->flatMap(fn($sale) => $sale['items']) // flatten items
        ->groupBy('product_id')
        ->map(function ($items) {
            return [
                'product_id'   => $items->first()['product_id'],
                'product_name' => $items->first()['product']['name'],
                'total_qty'    => $items->sum('quantity'),
                'total_sales'  => $items->sum('grand_total'),
            ];
        })
        ->sortByDesc('total_qty')
        ->take(5)       // get top 5
        ->values();

        $paymentSummary = collect($rawSales) // your raw data
        ->groupBy('payment_method')
        ->map(function ($group) {
            return [
                'name' => $group->first()['payment_method'] ?? "Tidak Diketahui",
                'value'          => $group->count(),
                'total_amount'   => $group->sum('total_price'),
            ];
        })
        ->values();
        $branchPerformance = collect($rawSales) // raw data
        ->groupBy('branch_id')
        ->map(function ($branchSales, $branchId) {
            return [
                'branch_id'     => $branchId,
                'branch' => $branchSales->first()->branch,
                'total_sales'   => $branchSales->sum('total_price'),
                'transaction_count' => $branchSales->count(),
            ];
        })
        ->sortByDesc('total_sales')
        ->values();

        $paymentSummaryChart = [
            'tooltip' => ['trigger' => "item"],
            'series' => [
                [
                    'name' => "Metode Pembayaran",
                    'type' => "pie",
                    'radius' => ['40%', '70%'],
                    'avoidLabelOverlap' => true,
                    'data' => $paymentSummary,
                ]
            ]
        ];

        $orderTypeSummary = collect($rawSales)
            ->groupBy(fn($sale) => $sale['order_type'])
            ->map(function ($salesGroup, $orderType) {
                $salesGroup = collect($salesGroup);
                return [
                    'name'        => $orderType,
                    'total_sales'       => $salesGroup->sum('total_price'),
                    'value' => $salesGroup->count(),
                ];
            })
            ->sortByDesc('total_sales')
            ->values();

        $orderTypeChart = [
            'tooltip' => ['trigger' => "item"],
            'series' => [
                [
                    'name' => "Metode Pembayaran",
                    'type' => "pie",
                    'radius' => ['40%', '70%'],
                    'avoidLabelOverlap' => true,
                    'data' => $orderTypeSummary,
                ]
            ]
        ];

        return view('user.report.sales', [
            'request' => $request,
            'omset' => $omset,
            'volume' => $volume,
            'omset_chart' => $omsetChart,
            'volume_chart' => $volumeChart,
            'myBranches' => $myBranches,
            'branchID' => $branchID,
            'topProducts' => $topProducts,
            'branchPerformance' => $branchPerformance,
            'paymentSummary' => $paymentSummary,
            'paymentSummaryChart' => $paymentSummaryChart,
            'orderTypeSummary' => $orderTypeSummary,
            'orderTypeChart' => $orderTypeChart,
        ]);
    }
    public function salesDetailReport(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $myBranches = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7);
        $endDate = $request->end_date ?? Carbon::now();
        $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }

        $sl = new Sales();
        if ($request->branch_id != "") {
            $sl = $sl->where('branch_id', $request->branch_id);
        } else {
            $sl = $sl->whereIn('branch_id', $myBranchIDs);
        }
        if ($request->q != "") {
            $sl = $sl->where('invoice_number', 'LIKE', '%'.$request->q.'%');
        }

        $sale = $sl->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(), 
            Carbon::parse($endDate)->endOfDay()
        ])
        ->with(['items', 'branch', 'customer', 'user'])
        ->orderBy('created_at', 'DESC');

        $sales = [];
        if ($isDownloading) {
            $sales = $sale->get();
        } else {
            $sales = $sale->paginate(25)->withQueryString();
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

        return view('user.report.sales_detail', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sales' => $sales,
            'request' => $request,
            'branches' => $myBranches,
        ]);
    }
    public function topSellingReport(Request $request) {
        $ranges = $this->userController->generateDateRangeIndexes($request->date_range ?? 'today');
        $filter = [
            ['status', 'PUBLISHED'],
            ['payment_status', 'PAID']
        ];
        if ($request->branch_id != "") {
            array_push($filter, ['branch_id', $request->branch_id]);
        }

        $rawSales = [];
        foreach ($ranges as $d => $date) {
            $dateBetween = [];
            
            $sale = Sales::where($filter);
            if ($date['start'] == $date['end']) {
                $sale = $sale->where('created_at', 'LIKE', '%'.$date['start'].'%');
            } else {
                $sale = $sale->whereBetween('created_at', [$date['start'], $date['end']]);
            }
            $sales = $sale->with(['items.product', 'branch'])->get();

            foreach ($sales as $item) {
                array_push($rawSales, $item);
            }
        }
        
        $topProducts = collect($rawSales) // your raw data
        ->flatMap(fn($sale) => $sale['items']) // flatten items
        ->groupBy('product_id')
        ->map(function ($items) {
            return [
                'product_id'   => $items->first()['product_id'],
                'product_name' => $items->first()['product']['name'],
                'total_qty'    => $items->sum('quantity'),
                'name' => $items->first()['product']['name'],
                'value'    => $items->sum('quantity'),
                'total_sales'  => $items->sum('grand_total'),
            ];
        })
        ->sortByDesc('total_qty')
        ->values();

        $me = me();
        $branchID = $request->branch_id;

        $chartOptions = [
            'tooltip' => ['trigger' => "item"],
            'series' => [
                [
                    'name' => "Metode Pembayaran",
                    'type' => "pie",
                    'radius' => ['40%', '70%'],
                    'avoidLabelOverlap' => true,
                    'data' => $topProducts,
                ]
            ]
        ];

        return view('user.report.top_selling', [
            'me' => $me,
            'request' => $request,
            'branchID' => $branchID,
            'topProducts' => $topProducts,
            'chartOptions' => $chartOptions,
        ]);
    }
    public function stockMovement(Request $request, $me = null) {
        if ($me == null) {
            $me = me();
        }
        $myBranchIDs = [];
        $myBranches = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7);
        $endDate = $request->end_date ?? Carbon::now();
        $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }

        $query = new Product();
        if ($request->q != "") {
            $query = $query->where('name', 'LIKE', '%'.$request->q.'%');
        }
        $productsRaw = null;
        if ($request->branch_id != null && $request->branch_id != "null") {
            $productsRaw = $query->where('branch_id', $request->branch_id);
        } else {
            $productsRaw = $query->whereIn('branch_id', $myBranchIDs);
        }
        $productsRaw = $productsRaw->with(['branch', 'images'])
        ->orderBy('updated_at', 'DESC');

        if ($isDownloading) {
            $productsRaw = $productsRaw->get();
            $products = $productsRaw;
        } else {
            $productsRaw = $productsRaw->paginate(25)->withQueryString();
            $products = $productsRaw->items();
        }


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
                $movements[$stock->movement->type] += $stock->quantity;
            }

            $products[$p]->movements = $movements;
        }

        if ($isDownloading) {
            $filename = "Pergerakan_Stok-Exported_at_" . Carbon::now()->isoFormat('DD-MMM-Y') . ".xlsx";

            return Excel::download(
                new MovementExport([
                    'products' => $products,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]),
                $filename
            );
        }

        if (in_array('api', $request->route()->middleware())) {
            return [
                'products' => $products,
            ];
        } else {
            return view('user.report.movement', [
                'request' => $request,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'products' => $products,
                'productsRaw' => $productsRaw,
                'branches' => $myBranches,
            ]);
        }
    }
    public function stockMovementDetail($productID, Request $request) {
        $me = me();
        $myBranches = [];
        $myBranchIDs = [];
        $startDate = $request->start_date ?? Carbon::now()->subDays(7);
        $endDate = $request->end_date ?? Carbon::now();
        $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $isDownloading = $request->download == 1;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }
        
        $myBranches = collect($myBranches);
        $movements = [];
        $product = Product::where('id', $productID)->with(['branch'])->first();
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

        if ($isDownloading) {
            $filename = "Detail_Pergerakan_Stok-Exported_at_" . Carbon::now()->isoFormat('DD-MMM-Y HH:mm') . ".xlsx";

            return Excel::download(
                new MovementDetailExport([
                    'product' => $product,
                    'movements' => $movements,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]),
                $filename
            );
        }

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
