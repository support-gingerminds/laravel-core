<?php

namespace Gingerminds\LaravelCore\Database\Seeders;

use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::pluck('name')->toArray();

        // Créer 5 utilisateurs et leur assigner un rôle aléatoire
        User::factory(5)->create()->each(function ($user) use ($roles) {
            $user->assignRole(fake()->randomElement($roles));
        });

        // Création de 5 contributeurs
        Contributor::factory(5)->create();
    }
}
