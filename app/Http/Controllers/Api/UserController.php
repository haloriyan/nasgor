<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CheckIn;
use App\Models\Product;
use App\Models\Purchasing;
use App\Models\Review;
use App\Models\Sales;
use App\Models\StockMovement;
use App\Models\StockOrder;
use App\Models\StockRequest;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function search(Request $request) {
        $users = User::where('name', 'LIKE', '%'.$request->q.'%')->take(25)->get();

        return response()->json([
            'users' => $users,
        ]);
    }
    public function auth(Request $request) {
        $user = $request->user('user');
        $user = me($user);

        return response()->json([
            'user' => $user,
            'token' => $request->bearerToken(),
        ]);
    }
    public function login(Request $request) {
        $u = User::where('email', $request->email);
        $user = $u->first();
        $message = "Kombinasi email dan password tidak tepat.";
        $token = null;
        $status = 403;

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('app')->plainTextToken;
                $user = me($user);
                $message = "Berhasil login.";
                $status = 200;
            }
        }

        return response()->json([
            'status' => $status,
            'user' => $user,
            'token' => $token,
            'message' => $message,
        ]);
    }
    public function switchBranch($accessID, Request $request) {
        $user = $request->user('user');

        $user = User::where('id', $user->id)->update([
            'current_access' => $accessID,
        ]);

        return response()->json([
            'user' => $user
        ]);
    }
    
    public function dashboard(Request $request, $branchID = null) {
        $user = me($request->user('user'));
        $omset = 0;
        $volume = 0;
        $myBranchIDs = [];
        $myBranches = [];
        $dateRange = $request->date_range ?? 'today';
        $ranges = generateDateRangeIndexes($dateRange);
        if ($branchID == null) {
            $branchID = $request->branch_id;
        }

        $labels = collect($ranges)->map(function ($date) use ($dateRange) {
            $format = $dateRange == "today" ? "HH:mm" : "DD MMM";
            return $date['start'] === $date['end']
                ? \Carbon\Carbon::parse($date['start'])->isoFormat($format)
                : \Carbon\Carbon::parse($date['start'])->isoFormat($format) . ' - ' . \Carbon\Carbon::parse($date['end'])->isoFormat($format);
        })->values()->toArray();

        // Define solid RGB colors for your branches
        $rawColors = [
            [255, 99, 132],   // red
            [54, 162, 235],   // blue
            [255, 206, 86],   // yellow
            [75, 192, 192],   // teal
            [153, 102, 255],  // purple
            [255, 159, 64],   // orange
        ];

        $omsetDatasets = [];
        $volumeDatasets = [];
        $legend = [];
        $theBranch = null;

        foreach ($user->accesses as $i => $access) {
            $branch = $theBranch == null ? $access->branch : $theBranch;
            if (!in_array($branch->id, $myBranchIDs)) {
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
        
        foreach ($myBranches as $branch) {
            $rgb = $rawColors[$i % count($rawColors)];
            $colorString = "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, OPACITY)";

            $omsetData = [];
            $volumeData = [];

            foreach ($ranges as $date) {
                $saleQuery = Sales::where([
                    ['branch_id', $branch->id],
                    ['status', 'PUBLISHED'],
                    ['payment_status', 'PAID']
                ]);

                if ($date['start'] === $date['end']) {
                    $saleQuery->where('created_at', 'LIKE', $date['start'] . '%');
                } else {
                    $saleQuery->whereBetween('created_at', [$date['start'], $date['end']]);
                }

                $sales = $saleQuery->get();
                $totalPrice = $sales->sum('total_price');
                $totalCount = $sales->count();

                $omset += $totalPrice;
                $volume += $totalCount;

                $omsetData[] = $totalPrice;
                $volumeData[] = $totalCount;
            }

            $legend[] = $branch->name;

            $omsetDatasets[] = [
                'data' => $omsetData,
                'color' => $colorString,
                'strokeWidth' => 2,
            ];

            $volumeDatasets[] = [
                'data' => $volumeData,
                'color' => $colorString,
                'strokeWidth' => 2,
            ];
        }

        $lowStocks = Product::whereIn('branch_id', $myBranchIDs)
            ->where('quantity', '<', 10)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'ASC')
            ->take(10)
            ->get();

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

        return response()->json([
            'omset' => $omset,
            'volume' => $volume,
            'labels' => $labels,
            'legend' => $legend,
            'omsetChart' => [
                'labels' => $labels,
                'datasets' => $omsetDatasets,
                'legend' => $legend,
            ],
            'volumeChart' => [
                'labels' => $labels,
                'datasets' => $volumeDatasets,
                'legend' => $legend,
            ],
            'lowStocks' => $lowStocks,
            'reviews' => $reviews,
            'sales' => $sales,
        ]);
    }

    public function sales(Request $request) {
        $user = $request->user('user');
        $user = me($user);

        $filter = [
            ['branch_id', $user->access->branch_id],
        ];

        if ($request->order_type != "SEMUA") {
            array_push($filter, ['order_type', $request->order_type]);
        }
        if ($request->payment_method != "SEMUA") {
            array_push($filter, ['payment_method', $request->payment_method]);
        }

        $sale = Sales::where($filter);
        if ($request->q != "") {
            $sale = $sale->whereHas('customer', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhere([
                ['invoice_number', 'LIKE', '%'.$request->q.'%'],
                ['branch_id', $user->access->branch_id]
            ]);
        }
        $sales = $sale->orderBy('created_at', 'DESC')
        ->with(['items', 'items.product.images', 'items.addons.addon', 'customer', 'review', 'branch', 'user'])
        ->paginate(25);

        return response()->json([
            'sales' => $sales,
        ]);
    }
    public function suppliers() {
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        return response()->json([
            'suppliers' => $suppliers,
        ]);
    }
    public function purchasing(Request $request) {
        $user = $request->user('user');
        $user = me($user);

        $purchasings = Purchasing::where('branch_id', $user->access->branch_id)
        ->with(['items.product.images', 'branch', 'supplier', 'staff', 'receiver'])
        ->orderBy('created_at', 'DESC')
        ->paginate(25);

        return response()->json([
            'purchasings' => $purchasings,
        ]);
    }
    public function opname(Request $request) {
        $user = me($request->user('user'));

        $opnames = StockMovement::where([
            ['branch_id', $user->access->branch_id],
            ['type', 'opname']
        ])
        ->with(['branch', 'user', 'items.product.images'])
        ->orderBy('created_at', 'DESC')
        ->paginate(25);

        return response()->json([
            'opnames' => $opnames,
        ]);
    }

    public function minta(Request $request) {
        $user = me($request->user('user'));
        $branches = Branch::where('id', '!=', $user->access->branch_id)
        ->orderBy('name', 'ASC')->get();

        $myRequests = StockRequest::where([
            ['is_accepted', null],
            ['seeker_branch_id', $user->access->branch_id],
        ])
        ->with(['product.images', 'seeker_branch', 'seeker_user', 'provider_branch', 'provider_user'])->get();
        $incomingRequests = StockRequest::where([
            ['is_accepted', null],
            ['provider_branch_id', $user->access->branch_id],
        ])
        ->with(['product.images', 'seeker_branch', 'seeker_user', 'provider_branch', 'provider_user'])->get();

        return response()->json([
            'branches' => $branches,
            'incoming_requests' => $incomingRequests,
            'my_requests' => $myRequests,
        ]);
    }
    public function stockOrder(Request $request) {
        $user = me($request->user('user'));

        $orders = StockOrder::whereNull('status')
        ->whereIn('seeker_branch_id', $user->branchesID)
        ->with(['product.images', 'taker_id'])
        ->orderBy('created_at', 'DESC')
        ->get();
        $histories = StockOrder::where('status', true)
        ->with(['product.images', 'taker_id'])
        ->orderBy('created_at', 'DESC')
        ->get();
        
        $orders = $this->stockOrderRestructure($orders);
        $histories = $this->stockOrderRestructure($histories);

        return response()->json([
            'orders' => $orders,
            'histories' => $histories,
            'user' => $user,
        ]);
    }
    public function stockOrderRestructure($orders) {
        return $orders->groupBy('product_id')->map(function ($items, $productId) {
            return [
                'identifier'     => Str::uuid(),
                'product_id'     => $productId,
                'branches'       => $items->pluck('seeker_branch')
                                        ->unique('id')
                                        ->values()
                                        ->map(fn($branch) => [
                                                'id'   => $branch->id,
                                                'name' => $branch->name,
                                        ]),
                'total_quantity' => $items->sum('quantity'), // ✅ from StockOrder level
                'product'        => $items->first()->product, // keep product info
                'orders'         => $items->values(),        // keep original orders if needed
            ];
        })->values();
    }
}
