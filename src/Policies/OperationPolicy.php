<?php

namespace TCG\Voyager\Policies;

use TCG\Voyager\Models\User;
use TCG\Voyager\Models\Operation;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the operation.
     *
     * @param  \TCG\Voyager\Models\User  $user
     * @param  \TCG\Voyager\Models\Operation  $operation
     * @return mixed
     */
    public function do(User $user, Operation $operation)
    {
        return ($user->role->operations()->find($operation->id))? true: false;
    }

}
