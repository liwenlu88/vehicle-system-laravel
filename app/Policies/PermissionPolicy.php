<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, Permission $permissions): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, Permission $permissions): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, Permission $permissions): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $user, Permission $permissions): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $user, Permission $permissions): bool
    {
        //
    }
}
