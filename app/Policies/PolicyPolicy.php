<?php

namespace App\Policies;

use App\Models\Policy;
use App\Models\User;

class PolicyPolicy
{
    /**
     * @param  User $user
     * @param  Policy $policy
     * @return bool
     */
    public function view(User $user, Policy $policy)
    {
        if($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
           return $user->managerOrganization()->id == $policy->organization_id;
        }

        return $user->policies()->where('policies.id', $policy->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
