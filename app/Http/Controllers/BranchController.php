<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function store(Request $request) {
        $toCreate = [
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = rand(1111, 9999) . "_" . $icon->getClientOriginalName();
            $toCreate['icon'] = $iconFileName;
            $icon->move(
                public_path('storage/branch_icons'),
                $iconFileName
            );
        }

        $branch = Branch::create($toCreate);

        return redirect()->route('branches')->with([
            'message' => "Berhasil menambahkan cabang baru"
        ]);
    }
    public function delete(Request $request) {
        $data = Branch::where('id', $request->id);
        $branch = $data->first();

        $data->delete();

        return redirect()->route('branches')->with([
            'message' => "Berhasil menghapus cabang " . $branch->name,
        ]);
    }
    public function detail($id, Request $request) {
        $branch = Branch::where('id', $id)->first();
        $message = Session::get('message');
        $tab = $request->tab == "" ? "detail" : $request->tab;
        $accesses = [];
        $roles = [];

        if ($tab == "access") {
            $accesses = UserAccess::where('branch_id', $id)->with(['user', 'role'])->get();
            $roles = Role::orderBy('name', 'ASC')->get();
        }

        return view('user.branch.detail', [
            'message' => $message,
            'branch' => $branch,
            'accesses' => $accesses,
            'tab' => $tab,
            'roles' => $roles,
        ]);
    }
    public function basicInfo($id, Request $request) {
        $data = Branch::where('id', $id);
        $branch = $data->first();

        $toUpdate = [
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = rand(1111, 9999)."_".$icon->getClientOriginalName();
            $deleteOldIcon = Storage::delete('public/branch_icons/' . $branch->icon);
            $toUpdate['icon'] = $iconFileName;
            $icon->move(
                public_path('storage/branch_icons'),
                $iconFileName,
            );
        }

        $data->update($toUpdate);

        return redirect()->route('branches.detail', $id)->with([
            'message' => "Berhasil mengubah informasi cabang"
        ]);
    }
}
