<?php

namespace Gingerminds\LaravelCore\Console\Commands\Security;

use Exception;
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gingerminds:create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un utilisateur (User + Contributor) et lui attribuer un rôle';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /**
         * @var User $userModel
         */
        $userModel = config('auth.providers.users.model');
        $roles     = Role::query()->pluck('name', 'id')->toArray();
        if (empty($roles)) {
            $this->error("Aucun rôle n'est défini. Exécutez les seeders de permissions/roles avant.");
            return self::FAILURE;
        }

        $role = $this->choice(
            "Quel rôle attribuer à l'utilisateur ?",
            $roles,
        );
        $email = $this->ask("Email de l'utilisateur ?");
        if ($userModel::where('email', $email)->exists()) {
            $this->error('Un utilisateur avec cet email existe déjà.');
            return self::FAILURE;
        }
        $lastname  = $this->ask('Nom de famille ?');
        $firstname = $this->ask('Prénom ?');
        $password  = $this->secret('Mot de passe ?');

        try {
            $user = $userModel::create([
                'email'             => $email,
                'password'          => bcrypt($password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($role);

            $contributor = Contributor::create([
                'lastname'  => $lastname,
                'firstname' => $firstname,
                'user_id'   => $user->id,
            ]);

            $this->line("Utilisateur créé avec succès (ID: {$user->id})");
        } catch (Exception $e) {
            $this->error('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }

        return self::SUCCESS;
    }
}
