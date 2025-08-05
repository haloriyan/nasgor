<?php

namespace App\Http\Controllers;

use App\Exports\CheckinExport;
use App\Models\AddOn;
use App\Models\Branch;
use App\Models\Category;
use App\Models\CheckIn;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Product;
use App\Models\Purchasing;
use App\Models\Review;
use App\Models\Role;
use App\Models\Sales;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\UserBranch;
use App\Models\UserRole;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function errorPage($code) {
        $errors = [
            '404' => [
                'title' => "Tidak Ditemukan",
                'description' => "Kami tidak dapat menemukan yang Anda cari",
            ],
            '403' => [
                'title' => "Tidak Ada Izin Akses",
                'description' => "Anda tidak memiliki izin akses untuk melihat laman ini.",
            ],
        ];

        return view('user.error', [
            'error' => $errors[$code],
            'code' => $code,
        ]);
    }
    public function login(Request $request) {
        if ($request->isMethod('GET')) {
            $message = Session::get('message');
            return view('user.login', [
                'message' => $message,
            ]);
        } else {
            $loggingIn = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if (!$loggingIn) {
                return redirect()->back()->withErrors([
                    'Kombinasi Email dan Password tidak tepat'
                ]);
            }

            return redirect()->route('dashboard');
        }
    }

    public function generateDateRangeIndexes($rangeType) {
        $format = 'Y-m-d';
        $now = Carbon::now();
        $start = null;
        $end = $now->copy();

        switch ($rangeType) {
            case 'last_7_days':
                $start = $now->copy()->subDays(6)->startOfDay();
                $end = $now->copy()->endOfDay();
                $period = CarbonPeriod::create($start, '1 day', $end);

                return collect($period)->map(fn($date) => [
                    'start' => $date->format($format),
                    'end' => $date->format($format),
                ])->all();

            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $period = CarbonPeriod::create($start, '1 day', $end);

                return collect($period)->map(fn($date) => [
                    'start' => $date->format($format),
                    'end' => $date->format($format),
                ])->all();

            case 'last_3_months':
            case 'last_6_months':
                $monthsToSubtract = $rangeType === 'last_3_months' ? 3 : 6;
                $start = $now->copy()->subMonths($monthsToSubtract)->startOfMonth();
                $end = $now->copy()->endOfMonth();

                $weeks = [];
                $current = $start->copy()->startOfWeek();
                while ($current <= $end) {
                    $weekStart = $current->copy();
                    $weekEnd = $current->copy()->endOfWeek();
                    if ($weekEnd > $end) {
                        $weekEnd = $end->copy();
                    }
                    $weeks[] = [
                        'start' => $weekStart->format($format),
                        'end' => $weekEnd->format($format),
                    ];
                    $current->addWeek();
                }
                return $weeks;

            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();

                $months = [];
                $current = $start->copy();
                while ($current <= $end) {
                    $monthStart = $current->copy()->startOfMonth();
                    $monthEnd = $current->copy()->endOfMonth();
                    $months[] = [
                        'start' => $monthStart->format($format),
                        'end' => $monthEnd->format($format),
                    ];
                    $current->addMonth();
                }
                return $months;

            case 'last_2_years':
                $start = $now->copy()->subYears(2)->startOfYear();
                $end = $now->copy()->endOfYear();

                $months = [];
                $current = $start->copy();
                while ($current <= $end) {
                    $monthStart = $current->copy()->startOfMonth();
                    $monthEnd = $current->copy()->endOfMonth();
                    $months[] = [
                        'start' => $monthStart->format($format),
                        'end' => $monthEnd->format($format),
                    ];
                    $current->addMonth();
                }
                return $months;

            default:
                return "Unknown range type: $rangeType";
        }
    }
    public function dashboard(Request $request) {
        $me = me();
        $myBranchIDs = [];
        $myBranches = [];
        $omsetSeries = [];
        $volumeSeries = [];
        $omset = 0;
        $volume = 0;

        foreach ($me->accesses as $access) {
            if (!in_array($access->branch_id, $myBranchIDs)) {
                array_push($myBranchIDs, $access->branch_id);
                array_push($myBranches, $access->branch);
            }
        }
        $myBranches = collect($myBranches);

        $ranges = $this->generateDateRangeIndexes($request->date_range ?? 'last_7_days');

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
                $sales = $sale->get();
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
                'data' => collect($ranges)->map(function ($date) {
                    if ($date['start'] == $date['end']) {
                        return Carbon::parse($date['start'])->isoFormat('DD MMM');
                    } else {
                        return Carbon::parse($date['start'])->isoFormat('DD MMM') . " - " . Carbon::parse($date['end'])->isoFormat('DD MMM');
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

        $lowStocks = Product::whereIn('branch_id', $myBranchIDs)
        ->where([
            ['quantity', '<', 10],
            ['quantity', '>', 0],
        ])->orderBy('quantity', 'ASC')->take(10)->get();

        $newCustomersCount = Customer::whereIn('branch_id', $myBranchIDs)
        ->whereBetween('created_at', [
            $ranges[0]['start'],
            $ranges[count($ranges) - 1]['end'],
        ])
        ->get(['id'])->count();

        $newCustomers = Customer::whereIn('branch_id', $myBranchIDs)
        ->orderBy('created_at', 'DESC')->take(5)->get();
        $sales = Sales::whereIn('branch_id', $myBranchIDs)
        ->where([
            ['status', 'PUBLISHED'],
            ['payment_status', 'PAID']
        ])
        ->with(['customer'])
        ->orderBy('created_at', 'DESC')->take(5)->get();

        $reviews = Review::whereIn('branch_id', $myBranchIDs)
        ->with(['customer'])
        ->orderBy('created_at', 'DESC')->take(5)->get();

        return view('user.dashboard', [
            'request' => $request,
            'omset' => $omset,
            'volume' => $volume,
            'sales' => $sales,
            'reviews' => $reviews,
            'newCustomersCount' => $newCustomersCount,
            'newCustomers' => $newCustomers,
            'omset_chart' => $omsetChart,
            'volume_chart' => $volumeChart,
            'lowStocks' => $lowStocks,
            'myBranches' => $myBranches,
        ]);
    }
    public function accessRole() {
        $roles = Role::with(['accesses'])->get();

        return view('user.accessRole.index', [
            'roles' => $roles,
        ]);
    }
    public function product(Request $request) {
        $me = me();
        $products = [];
        $categories = [];
        $addons = [];
        $message = Session::get('message');
        $tab = $request->tab == "" ? "produk" : $request->tab;
        $categories = Category::orderBy('name', 'ASC')->with(['products'])->get();

        if ($request->tab == "produk" || $request->tab == "") {
            $tab = "produk";
            $products = Product::where([
                ['branch_id', $me->access->branch_id],
            ])->orderBy('updated_at', 'DESC')
            ->with(['images', 'categories'])->paginate(25);
        }

        if ($request->tab == "addon") {
            $addons = AddOn::orderBy('created_at', 'DESC')
            ->whereHas('products', function ($query) use ($me) {
                $query->where('branch_id', $me->access->branch_id);
            })
            ->with(['products' => function ($query) use ($me) {
                $query->where('branch_id', $me->access->branch_id);
            }])
            ->get();
        }

        return view('user.product.index', [
            'request' => $request,
            'message' => $message,
            'categories' => $categories,
            'addons' => $addons,
            'products' => $products,
            'tab' => $tab,
            'me' => $me,
        ]);
    }
    public function inventory(Request $request) {
        $me = me();
        $tab = $request->tab == "" ? "inbound" : $request->tab;
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $purchasings = Purchasing::whereNull('inventory_id')->where([
            ['branch_id', $me->access->branch_id]
        ])
        ->orderBy('created_at', 'DESC')
        ->get();
        $branches = [];

        $filter = [
            ['branch_id', $me->access->branch_id],
            ['type', $tab]
        ];

        if ($request->supplier_id != "") {
            array_push($filter, ['supplier_id', $request->supplier_id]);
        }
        if ($request->status != "") {
            array_push($filter, ['status', $request->status]);
        }

        $inv = StockMovement::where($filter);
        if ($request->q != "") {
            $inv = $inv->whereHas('items.product', function ($query) use ($request) {
                $query->where('products.name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $inventories = $inv->with(['items.product', 'supplier'])->orderBy('created_at', 'DESC')
        ->paginate(25);

        if ($tab == "outbound") {
            $branches = Branch::where('id', '!=', $me->access->branch_id)
            ->orderBy('name', 'ASC')->get();
        }

        return view('user.inventory.index', [
            'request' => $request,
            'tab' => $tab,
            'suppliers' => $suppliers,
            'purchasings' => $purchasings,
            'inventories' => $inventories,
            'branches' => $branches,
        ]);
    }
    public function customer(Request $request) {
        $me = me();
        $type = null;
        $tab = $request->tab == "" ? "customer" : $request->tab;
        $message = Session::get('message');
        $reviews = [];
        $averageRating = null;
        $totalReviews = 0;
        $reviewProportion = 0;

        $types = CustomerType::where('branch_id', $me->access->branch_id)
        ->orWhereNull('branch_id')
        ->orderBy('name', 'ASC')->with(['customers'])->get();

        $customers = Customer::where('branch_id', $me->access->branch_id)
        ->orWhereNull('branch_id')
        ->orderBy('created_at', 'DESC')->with(['types'])->paginate(25);
        
        if ($tab == "customer_type_detail") {
            $type = CustomerType::where('id', $request->type_id)->with(['customers'])->first();
        }
        if ($tab == "review") {
            $filter = [['branch_id', $me->access->branch_id]];

            // Getting average rating
            $allReviews = Review::where($filter)->get(['id', 'rating']);
            $averageRating = $allReviews->avg('rating');
            $totalReviews = $allReviews->count();

            $allSales = Sales::where($filter)->get(['id']);
            $reviewProportion = number_format($totalReviews / $allSales->count() * 100, 2);

            if ($request->filter_rating != "") {
                array_push($filter, ['rating', '<=', $request->filter_rating]);
            }
            
            $reviews = Review::where($filter)
            ->orderBy('created_at', 'DESC')->with(['order.items.product', 'customer'])->paginate(25);
        }

        return view('user.customer.index', [
            'request' => $request,
            'message' => $message,
            'tab' => $tab,
            'me' => $me,
            'types' => $types,
            'type' => $type,
            'customers' => $customers,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'reviewProportion' => $reviewProportion,
        ]);
    }
    public function supplier(Request $request) {
        $suppliers = Supplier::orderBy('updated_at', 'DESC')->paginate(25);
        $message = Session::get('message');

        return view('user.supplier.index', [
            'request' => $request,
            'message' => $message,
            'suppliers' => $suppliers,
        ]);
    }
    public function purchasing(Request $request) {
        $me = me();
        $message = Session::get('message');
        $tab = $request->tab == "" ? "draft" : $request->tab;
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $purchasings = Purchasing::where([
            ['branch_id', $me->access->branch_id],
            ['status', strtoupper($tab)]
        ])->paginate(25);

        return view('user.purchasing.index', [
            'request' => $request,
            'message' => $message,
            'tab' => $tab,
            'suppliers' => $suppliers,
            'purchasings' => $purchasings,
        ]);
    }
    public function switchBranch($id) {
        $me = me();

        User::where('id', $me->id)->update([
            'current_access' => $id
        ]);

        return redirect()->back();
    }
    public function users(Request $request) {
        $me = me();
        $users = User::with(['accesses'])->paginate(15);
        $message = Session::get('message');
        $branches = Branch::orderBy('name', 'ASC')->get();
        $roles = Role::orderBy('name', 'ASC')->get();

        return view('user.users', [
            'me' => $me,
            'users' => $users,
            'message' => $message,
            'branches' => $branches,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $access = UserAccess::create([
            'role_id' => $request->role_id,
            'branch_id' => $request->branch_id,
            'user_id' => $user->id,
        ]);
        
        User::where('id', $user->id)->update([
            'current_access' => $access->id,
        ]);

        return redirect()->route('users')->with([
            'message' => "Berhasil menambahkan " . $request->name,
        ]);
    }
    public function branchSettings() {
        return view('user.branch.settings');
    }
    public function checkin(Request $request) {
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');
        $checkins = [];
        
        $query = CheckIn::orderBy('created_at', 'DESC')
        ->whereHas('user', function ($query) use ($request) {
            $query->where('name', 'LIKE', '%'.$request->q.'%');
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['user', 'branch']);

        $isDownloading = $request->download == 1;

        if ($isDownloading) {
            $checkins = $query->get();
            $filename = "Absensi";
            if ($request->q != "") {
                $users = User::where('name', 'LIKE', '%'.$request->q.'%')->get(['name']);
                $filename .= "-" . implode("-", $users->pluck('name'));
            }
            $filename .= "-Exported_at_" . Carbon::now()->isoFormat('DD-MMM-Y') . ".xlsx";

            return Excel::download(
                new CheckinExport([
                    'checkins' => $checkins,
                ]),
                $filename
            );
        } else {
            $checkins = $query->paginate(25);
        }

        return view('user.checkin.index', [
            'request' => $request,
            'checkins' => $checkins,
        ]);
    }
    public function profile() {
        $me = me();
        $message = Session::get('message');
        
        return view('user.profile', [
            'me' => $me,
            'message' => $message,
        ]);
    }
    public function updateProfile(Request $request) {
        $me = me();
        $toUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password != "") {
            $toUpdate['password'] = bcrypt($request->password);
        }

        User::where('id', $me->id)->update($toUpdate);

        if ($request->password != "") {
            Auth::logout();
            return redirect()->route('login')->with([
                'message' => "Berhasil mengubah password. Mohon login kembali menggunakan password baru"
            ]);
        }

        return redirect()->route('profile')->with([
            'message' => "Berhasil mengubah profil"
        ]);
    }
    public function branches(Request $request) {
        $branches = Branch::orderBy('updated_at', 'DESC')->with(['accesses'])->get();
        $message = Session::get('message');

        return view('user.branch.index', [
            'branches' => $branches,
            'message' => $message,
        ]);
    }
    public function sales(Request $request) {
        $me = me();
        $sale = Sales::where([
            ['branch_id', $me->access->branch_id]
        ]);

        if ($request->q != "") {
            $sale = $sale->whereHas('customer', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhere([
                ['invoice_number', 'LIKE', '%'.$request->q.'%'],
                ['branch_id', $me->access->branch_id]
            ]);
        }

        $statusFilter = [];
        $status = $request->status ?? "DRAFT";
        if ($request->status) {
            array_push($statusFilter, ['status', $request->status]);
        }
        if ($request->payment_status) {
            array_push($statusFilter, ['payment_status', $request->payment_status]);
        }
        $sale = $sale->where($statusFilter);

        $sales = $sale->orderBy('created_at', 'DESC')->with(['items', 'customer'])
        ->paginate(25);

        return view('user.sales.index', [
            'request' => $request,
            'sales' => $sales,
            'me' => $me,
        ]);
    }
}
