<?php

namespace Gingerminds\LaravelCore\Providers;

use Gingerminds\LaravelCore\Models\Permission\Permission;
use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Policies\Permission\PermissionPolicy;
use Gingerminds\LaravelCore\Policies\Role\RolePolicy;
use Gingerminds\LaravelCore\Policies\User\ContributorPolicy;
use Gingerminds\LaravelCore\Policies\User\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\PermissionRegistrar;

class LaravelCoreAuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Contributor::class => ContributorPolicy::class,
        User::class        => UserPolicy::class,
        Role::class        => RolePolicy::class,
        Permission::class  => PermissionPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super-Admin') ? true : null;
        });

        app(PermissionRegistrar::class)
            ->registerPermissions(app(\Illuminate\Contracts\Auth\Access\Gate::class));

        $this->registerPolicies();
    }
}
