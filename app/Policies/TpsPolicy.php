<?php

namespace App\Policies;

use App\Models\Tps;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TpsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == 'super_admin' || $user->role == 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tps $tps): bool
    {
        return true;
    }

    public function deleteAny(User $user): bool
    {
        return $user->role == 'super_admin';
    }
        
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'super_admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tps $tps): bool
    {
        return $user->role == 'super_admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tps $tps): bool
    {
        return $user->role == 'super_admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tps $tps): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tps $tps): bool
    {
        return true;
    }
}
