<?php

namespace App\Policies;

use App\SmallFarmer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmallFarmerPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    // public function before($user, $ability) {
    //     if ($user->is_admin) {
    //         return true;
    //     }
    // }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // return $user->is_admin;
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmallFarmer  $smallFarmer
     * @return mixed
     */
    public function view(User $user, SmallFarmer $smallFarmer)
    {
        return $user->user_id == $smallFarmer->nonghyup_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmallFarmer  $smallFarmer
     * @return mixed
     */
    public function update(User $user, SmallFarmer $smallFarmer)
    {
        return $user->user_id === $smallFarmer->nonghyup_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmallFarmer  $smallFarmer
     * @return mixed
     */
    public function delete(User $user, SmallFarmer $smallFarmer)
    {
        // return $user->user_id === $smallFarmer->nonghyup_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmallFarmer  $smallFarmer
     * @return mixed
     */
    public function restore(User $user, SmallFarmer $smallFarmer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmallFarmer  $smallFarmer
     * @return mixed
     */
    public function forceDelete(User $user, SmallFarmer $smallFarmer)
    {
        //
    }
}
