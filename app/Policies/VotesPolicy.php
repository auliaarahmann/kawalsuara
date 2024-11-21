<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Votes;
use Illuminate\Auth\Access\Response;

class VotesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == 'super_admin' || $user->role == 'admin' || $user->role == 'operator' || $user->role == 'saksi';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Votes $votes): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'super_admin' || $user->role == 'saksi';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Votes $votes): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Votes $votes): bool
    {
        return $user->role == 'super_admin' || $user->role == 'admin';
    }

    public function deleteAny(User $user): bool
    {
        return $user->role == 'super_admin' || $user->role == 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Votes $votes): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Votes $votes): bool
    {
        return true;
    }
}
