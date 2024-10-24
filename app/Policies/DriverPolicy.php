<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Driver;
use Illuminate\Auth\Access\Response;

class DriverPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Driver $driver): bool
    {
        //
    }
}
