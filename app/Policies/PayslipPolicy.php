<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Payslip;
use App\Models\User;

class PayslipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Payslip');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('view Payslip');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Payslip');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('update Payslip');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('delete Payslip');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Payslip');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('restore Payslip');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Payslip');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('replicate Payslip');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Payslip');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payslip $payslip): bool
    {
        return $user->checkPermissionTo('force-delete Payslip');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Payslip');
    }
}
