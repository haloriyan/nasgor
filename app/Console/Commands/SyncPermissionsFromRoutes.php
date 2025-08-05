<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;

class SyncPermissionsFromRoutes extends Command
{
    protected $signature = 'permissions:sync';
    protected $description = 'Sync permissions based on route group names';

    public function handle()
    {
        $routes = Route::getRoutes();
        $groups = [];

        foreach ($routes as $route) {
            $name = $route->getName(); // e.g., 'orders.index'
            if (!$name || !str_contains($name, '.')) continue;

            $group = explode('.', $name)[0]; // extract 'orders'

            $groups[$group] = true; // collect unique groups
        }

        $created = 0;

        foreach (array_keys($groups) as $group) {
            $key = $group;
            $permission = Permission::firstOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'description' => 'Access to ' . ucfirst(str_replace('-', ' ', $group)) . ' module'
                ]
            );

            if ($permission->wasRecentlyCreated) {
                $created++;
                $this->info("Created permission: {$key}");
            }
        }

        $this->info("✅ Synced {$created} new permission(s).");
    }
}
