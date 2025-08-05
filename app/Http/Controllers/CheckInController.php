<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function detail($id) {
        $check = CheckIn::where('id', $id)->with(['user', 'branch'])->first();

        return view('user.checkin.detail', [
            'check' => $check,
        ]);
    }
}
