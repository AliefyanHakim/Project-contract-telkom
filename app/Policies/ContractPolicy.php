<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, Contract $contract): bool
    {
        if (
            $user->isManager() ||
            $user->isSupportInputter() ||
            $user->isSupportPaycall()
        ) {
            return true;
        }

    return $contract->owner_am_id === $user->id;
    }

    public function create(User $user): bool
    {
        return
            $user->isManager()
            || $user->isAccountManager()
            || $user->isSupportInputter();
    }

    public function update(User $user, Contract $contract): bool
    {
        if (
            $user->isManager() ||
            $user->isSupportInputter()
        ) {
            return true;
        }

        if ($user->isSupportPaycall()) {
            return true;
        }

        return $contract->owner_am_id === $user->id;
    }
}
