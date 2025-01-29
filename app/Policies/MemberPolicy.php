<?php

namespace App\Policies;

use App\Models\User;

class MemberPolicy
{
    /**
     * Determine whether the user can view any Member models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Member');
    }

    /**
     * Determine whether the user can view the Member model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('view Member');
    }

    /**
     * Determine whether the user can create a Member.
     */
    public function create(User $user): bool
    {
        return $user->can('create Member');
    }

    /**
     * Determine whether the user can update a Member.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('update Member');
    }

    /**
     * Determine whether the user can delete a Member.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->can('delete Member');
    }

    /**
     * ... and so on (restore, forceDelete, etc.) ...
     */
}
