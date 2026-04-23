<?php

namespace Gingerminds\LaravelCore\Policies\Role;

use Gingerminds\LaravelCore\Models\Role\Role;
use Gingerminds\LaravelCore\Models\User\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        if (! $user->can('manage roles')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $role->entity_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage roles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if (! $user->can('manage roles')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $role->entity_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if (! $user->can('manage roles')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $role->entity_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
