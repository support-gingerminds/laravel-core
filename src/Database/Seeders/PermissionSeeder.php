<?php

namespace Gingerminds\LaravelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\Role\Role;
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
        Permission::updateOrCreate(['name' => 'access admin']);
        Permission::updateOrCreate(['name' => 'view dashboard']);

        Permission::updateOrCreate(['name' => 'view users']);
        Permission::updateOrCreate(['name' => 'edit users']);
        Permission::updateOrCreate(['name' => 'delete users']);

        Permission::updateOrCreate(['name' => 'view permissions']);
        Permission::updateOrCreate(['name' => 'edit permissions']);
        Permission::updateOrCreate(['name' => 'delete permissions']);

        Permission::updateOrCreate(['name' => 'view contributors']);
        Permission::updateOrCreate(['name' => 'edit contributors']);
        Permission::updateOrCreate(['name' => 'delete contributors']);

        Permission::updateOrCreate(['name' => 'manage roles']);

        $this->command->info('Permissions table seeded!');
        // updateOrCreate roles and assign existing permissions

        Role::updateOrCreate(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        $this->command->info('Role Super-Admin seeded!');

        $role2 = Role::updateOrCreate(['name' => 'Admin']);
        $role2->givePermissionTo('access admin');
        $role2->givePermissionTo('view dashboard');
        $this->command->info('Role Admin seeded!');
    }
}
