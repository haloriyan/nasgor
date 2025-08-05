<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Branch
        $branch = Branch::firstOrCreate(['name' => 'Pusat'], [
            'address' => 'Jl. Pusat No. 1'
        ]);
        $secondBranch = Branch::firstOrCreate(['name' => 'Daerah'], [
            'address' => 'Jl. Daerah No. 12'
        ]);

        // 2. Create Admin User
        $user = User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Administrator',
            'password' => Hash::make('123456'), // Change this in production
        ]);

        // 3. Create Role
        $role = Role::firstOrCreate(['name' => 'Super Admin'], [
            'description' => 'Full access role',
            'multibranch' => true,
        ]);

        // 4. Sync Route Group Permissions
        $routes = Route::getRoutes();
        $groups = [];

        foreach ($routes as $route) {
            $name = $route->getName(); // e.g., 'orders.index'
            if (!$name || !str_contains($name, '.')) continue;

            $group = explode('.', $name)[0]; // 'orders'
            $groups[$group] = true;
        }

        foreach (array_keys($groups) as $group) {
            Permission::firstOrCreate(
                ['key' => $group],
                [
                    'group' => $group,
                    'description' => 'Access to ' . ucfirst(str_replace('-', ' ', $group)) . ' module'
                ]
            );
        }

        // 5. Attach All Permissions to Super Admin Role
        $allPermissions = Permission::pluck('id');
        $role->permissions()->sync($allPermissions);

        // 6. Assign Role to User for This Branch
        $assignedAccess = UserAccess::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'branch_id' => $branch->id,
        ]);
        $assignedSecondAccess = UserAccess::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'branch_id' => $secondBranch->id,
        ]);
        
        $user->update([
            'current_access' => $assignedAccess->id,
        ]);

        $this->command->info("✅ Super Admin setup complete!");
        $this->command->info("👤 Email: admin@admin.com");
        $this->command->info("🔐 Password: 123456");
    }
}
