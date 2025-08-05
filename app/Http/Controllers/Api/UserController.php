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
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $message = null;
        $token = null;

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('app')->plainTextToken;
            $user = me($user);
        } else {
            $user = null;
            $message = "Kombinasi email dan password tidak tepat.";
        }

        return response()->json([
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
    
    public function dashboard(Request $request) {
        $user = me($request->user('user'));
        $omset = 0;
        $volume = 0;
        $myBranchIDs = [];
        $ranges = generateDateRangeIndexes($request->date_range ?? 'last_7_days');

        $labels = collect($ranges)->map(function ($date) {
            return $date['start'] === $date['end']
                ? \Carbon\Carbon::parse($date['start'])->format('d M')
                : \Carbon\Carbon::parse($date['start'])->format('d M') . ' - ' . \Carbon\Carbon::parse($date['end'])->format('d M');
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

        foreach ($user->accesses as $i => $access) {
            $branch = $access->branch;
            if (!in_array($branch->id, $myBranchIDs)) {
                $myBranchIDs[] = $branch->id;
            }

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

        $sale = Sales::where('branch_id', $user->access->branch_id);
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
}
