<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CheckIn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function check(Request $request) {
        $user = $request->user('user');
        $user = me($user);
        $coordinates = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        $image = $request->file('image');
        $imageFileName = $image->getClientOriginalName();
        $canStoreImage = true;
        $maximumDistance = 5000; // in meters
        $type = "Cek-in";

        $nearestBranch = Branch::selectRaw("
            branches.*, 
            (6371000 * acos(
                cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance
        ", [$coordinates['latitude'], $coordinates['longitude'], $coordinates['latitude']])
        ->having("distance", "<", $maximumDistance)
        ->orderBy("distance")
        ->first();

        if ($nearestBranch == null) {
            return response()->json([
                'message' => "Tidak dapat menemukan cabang terdekat",
                'success' => false,
            ]);
        }
        
        $att = CheckIn::where([
            ['user_id', $user->id],
            ['branch_id', $nearestBranch->id],
            ['in_at', 'LIKE', "%".Carbon::now()->format('Y-m-d')."%"],
        ]);
        $attend = $att->first();

        if ($attend == null) {
            $attend = CheckIn::create([
                'user_id' => $user->id,
                'branch_id' => $nearestBranch->id,
                'distance_from_branch' => $nearestBranch->distance,
                'coordinates' => json_encode($coordinates),
                'in_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'in_photo' => $imageFileName,
            ]);
        } else {
            if ($attend->out_at == null) {
                $type = "Cek-out";
                $duration = Carbon::parse($attend->in_at)->diffInMinutes(Carbon::now());
                $att->update([
                    'out_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'out_photo' => $imageFileName,
                    'duration' => $duration,
                ]);
            } else {
                $canStoreImage = false;
            }
        }

        if ($canStoreImage) {
            $image->move(
                public_path('storage/check_in_images'),
                $imageFileName
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil " . $type . " di " . $nearestBranch->name,
        ]);
    }
    public function history(Request $request) {
        $user = $request->user('user');
        $histories = CheckIn::where('user_id', $user->id)
        ->with(['branch'])
        ->orderBy('in_at', 'DESC')->paginate(25);

        return response()->json([
            'histories' => $histories,
        ]);
    }
}
