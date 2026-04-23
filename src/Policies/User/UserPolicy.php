<?php

namespace Gingerminds\LaravelCore\Policies\User;

use Gingerminds\LaravelCore\Models\User\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $userEditable): bool
    {
        if (!$user->can('view users')) {
            return false;
        }

        if (!$user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $userEditable->contributor?->entity_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('edit users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $userEditable): bool
    {
        if (! $user->can('edit users')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $userEditable->contributor?->entity_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $userEditable): bool
    {
        if (! $user->can('delete users')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $userEditable->contributor?->entity_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $userEditable): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $userEditable): bool
    {
        return false;
    }
}
