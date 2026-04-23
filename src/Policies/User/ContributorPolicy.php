<?php

namespace Gingerminds\LaravelCore\Policies\User;

use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;

class ContributorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view contributors');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contributor $contributor): bool
    {
        if (!$user->can('view contributors')) {
            return false;
        }

        if (!$user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $contributor->entity_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('edit contributors');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contributor $contributor): bool
    {
        if (! $user->can('edit contributors')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $contributor->entity_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contributor $contributor): bool
    {
        if (! $user->can('delete contributors')) {
            return false;
        }

        if (! $user->contributor) {
            return false;
        }

        return $user->contributor->entity_id === $contributor->entity_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contributor $contributor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contributor $contributor): bool
    {
        return false;
    }
}
