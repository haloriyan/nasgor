<?php

use App\Models\Branch;
use App\Models\User;
use App\Models\UserBranch;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

function getBranchesAndPolicies($user) {
    $user = User::where('id', $user->id)->with(['accesses.branch', 'accesses.role'])->first();
    $branches = collect($user->accesses->pluck('branch'))->unique('id')->values(); // Prevent branches data redundant
    $roles = collect($user->accesses->pluck('role'))->unique('id')->values();
    $accesses = $user->accesses;

    $policies = [];

    foreach ($accesses as $access) {
        if ($access->branch->id == $user->branch->id) {
            $role = $access->role;
            $resources = json_decode($role->resources);
            foreach ($resources as $res) {
                if (!in_array($res, $policies)) {
                    array_push($policies, $res);
                }
            }
        }
    }

    $user->setRelation('branches', collect($branches));
    $user->setRelation('policies', collect($policies));
    
    return $user;
}
function me($user = null) {
    if ($user == null) {
        $user = Auth::user();
    } else {
        $theUser = User::where('id', $user->id)->with(['accesses.branch', 'accesses.role', 'access.branch', 'access.role'])->first();
        $user->accesses = $theUser->accesses;
        $user->access = $theUser->access;
    }
    $permissions = [];
        
    if ($user != null) {
        foreach ($user->access->role->permissions as $perm) {
            array_push($permissions, $perm->key);
        }
        $user->permissions = $permissions;

        $myBranches = [];
        $myBranchesID = [];
        foreach ($user->accesses as $acc) {
            array_push($myBranches, $acc->branch);
            array_push($myBranchesID, $acc->branch_id);
        }

        $user->branches = $myBranches;
        $user->branchesID = $myBranchesID;
    }

    return $user;
}
function currency_encode($angka, $currencyPrefix = 'Rp', $thousandSeparator = '.', $zeroLabel = 'Gratis') {
	if (!$angka) {
		return "Tidak ada data";
	}
    return $currencyPrefix.' '.strrev(implode($thousandSeparator,str_split(strrev(strval($angka)),3)));
}
function currency_decode($rupiah) {
    return intval(preg_replace("/,.*|[^0-9]/", '', $rupiah));
}
function initial($name) {
    $names = explode(" ", $name);
    $toReturn = $names[0][0];
    if (count($names) > 1) {
        $toReturn .= $names[count($names) - 1][0];
    }

    return strtoupper($toReturn);
}

function sanitizeWhatsapp($number, $returnPrefix = "0") {
    // Remove spaces, dashes, and other non-numeric except +
    $number = preg_replace('/[^\d+]/', '', $number);

    // If starts with +62, remove it
    if (strpos($number, '+62') === 0) {
        $number = substr($number, 3);
    }
    // If starts with 62 (without +), remove it
    elseif (strpos($number, '62') === 0) {
        $number = substr($number, 2);
    }
    // If starts with 0, remove it
    elseif (strpos($number, '0') === 0) {
        $number = substr($number, 1);
    }

    if ($number !== "") {
        $number = $returnPrefix.$number;
    }

    return $number;
}


function generateDateRangeIndexes($rangeType) {
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