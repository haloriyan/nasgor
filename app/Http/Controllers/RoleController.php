<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\UserBranch;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function store(Request $request) {
        $role = Role::create([
            'name' => $request->name,
            'multibranch' => $request->multibranch,
        ]);

        // Assign all permissions
        $permissions = Permission::all();
        foreach ($permissions as $perm) {
            $assign = PermissionRole::create([
                'role_id' => $role->id,
                'permission_id' => $perm->id,
            ]);
        }

        return redirect()->route('accessRole.detail', $role->id)->with([
            'message' => "Peran " . $role->name . " berhasil dibuat"
        ]);
    }
    public function detail($id, Request $request) {
        $tab = $request->tab == "" ? "resources" : $request->tab;
        $permissions = Permission::orderBy('key', 'ASC')->get();
        $users = [];
        $branches = Branch::orderBy('name', 'ASC')->get();

        $role = Role::where('id', $id)->with([
            'permissions', 
            'accesses', 'accesses.branch', 'accesses.user',
        ])
        ->first();
        
        $permissionKeys = [];

        foreach ($role->permissions as $perm) {
            array_push($permissionKeys, $perm->key);
        }

        $role->permission_keys = $permissionKeys;

        return view('user.accessRole.detail', [
            'role' => $role,
            'branches' => $branches,
            'permissions' => $permissions,
            'tab' => $tab,
        ]);
    }
    public function details($id, Request $request) {
        $me = me();
        $role = Role::where('id', $id)->with(['users.user', 'users.branch'])->first();
        $branches = [];
        $users = [];

        if ($request->tab == "users") {
            $users = User::orderBy('name', 'ASC')
            ->with([
                'accesses' => function ($query) use ($role) {
                    $query->where('role_id', $role->id);
                },
                'accesses.role',
                'accesses.branch'
            ])
            ->get(['id', 'name']);

            foreach ($users as $u => $user) {
                $users[$u]->available_branches = Branch::whereNotIn('id', $user->accesses->pluck('branch_id'))->get();
            }
        }

        return view('user.accessRole.detail', [
            'me' => $me,
            'role' => $role,
            'branches' => $branches,
            'users' => $users,
            'tab' => $request->tab == "" ? "resources" : $request->tab,
        ]);
    }
    public function togglePermission($roleID, $permissionID) {
        $piv = PermissionRole::where([
            ['role_id', $roleID],
            ['permission_id', $permissionID],
        ]);
        $pivot = $piv->first();

        if ($pivot == null) {
            $pivot = PermissionRole::create([
                'role_id' => $roleID,
                'permission_id' => $permissionID,
            ]);
        } else {
            $piv->delete();
        }

        return redirect()->route('accessRole.detail', $roleID);
    }
    public function toggleResource($id, $resource) {
        $ro = Role::where('id', $id);
        $role = $ro->first();
        $resource = base64_decode($resource);
        $resources = json_decode($role->resources) ?? [];

        if (in_array($resource, $resources)) {
            array_splice($resources, array_search($resource, $resources), 1);
        } else {
            array_push($resources, $resource);
        }

        $ro->update([
            'resources' => json_encode($resources)
        ]);

        return redirect()->route('accessRole.detail', $id);
    }
    public function assignAccess(Request $request) {
        $userIDs = json_decode($request->user_ids);

        foreach ($userIDs as $userID) {
            $check = UserAccess::where([
                ['user_id', $userID],
                ['role_id', $request->role_id],
                ['branch_id', $request->branch_id]
            ])->first();

            if ($check == null) {
                UserAccess::create([
                    'user_id' => $userID,
                    'role_id' => $request->role_id,
                    'branch_id' => $request->branch_id,
                ]);
            }
        }

        return redirect()->back()->with([
            'message' => "Berhasil menetapkan user",
        ]);
    }
    public function removeAccess($id) {
        $u = UserAccess::where('id', $id);
        $access = $u->first();
        
        $u->delete();
        return redirect()->back()->with([
            'message' => "Berhasil menghapus hak akses"
        ]);
        // return redirect()->route('accessRole.detail', [
        //     'id' => $access->role_id,
        // ]);
    }
}
