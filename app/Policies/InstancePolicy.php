<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Instance;
use App\Models\User;

class InstancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Instance');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('view Instance');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Instance');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('update Instance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('delete Instance');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Instance');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('restore Instance');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Instance');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('replicate Instance');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Instance');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Instance $instance): bool
    {
        return $user->checkPermissionTo('force-delete Instance');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Instance');
    }
}
