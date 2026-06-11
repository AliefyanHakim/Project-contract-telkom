<?php

namespace App\Policies;

use App\Models\User;

class ContractPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
   public function create(User $user)
    {
        return in_array(
            $user->role_id,
            [
                User::ROLE_ACCOUNT_MANAGER,
                User::ROLE_SUPPORT_INPUTTER,
                User::ROLE_SUPPORT_PAYCALL,
            ]
        );
    } 
}
