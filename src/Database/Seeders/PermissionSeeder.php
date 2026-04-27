<?php

namespace Gingerminds\LaravelCore\Database\Seeders;

use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\Role\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // updateOrCreate permissions
        Permission::updateOrCreate(['name' => 'access admin', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'view users', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'edit users', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'delete users', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'view permissions', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'edit permissions', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'delete permissions', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'view settings', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'view contributors', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'edit contributors', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'delete contributors', 'guard_name' => 'web']);

        Permission::updateOrCreate(['name' => 'manage roles', 'guard_name' => 'web']);

        $this->command->info('Permissions table seeded!');
        // updateOrCreate roles and assign existing permissions

        Role::updateOrCreate(['name' => 'Super-Admin', 'guard_name' => 'web']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $this->command->info('Role Super-Admin seeded!');

        $role2 = Role::updateOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $role2->givePermissionTo('access admin');
        $role2->givePermissionTo('view dashboard');
        $this->command->info('Role Admin seeded!');
    }
}
