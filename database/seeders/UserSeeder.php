<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'role_id' => User::ROLE_MANAGER,
            'name' => 'Manager',
            'email' => 'manager@telkom.com',
            'password' => 'manager123',
            'status' => 'active',
        ]);

        User::create([
            'role_id' => User::ROLE_ACCOUNT_MANAGER,
            'name' => 'Account Manager 1',
            'email' => 'AM1@telkom.com',
            'password' => 'AM123',
            'status' => 'active',
        ]);

        User::create([
            'role_id' => User::ROLE_ACCOUNT_MANAGER,
            'name' => 'Account Manager 2',
            'email' => 'AM2@telkom.com',
            'password' => 'AM234',
            'status' => 'active',
        ]);

        User::create([
            'role_id' => User::ROLE_ACCOUNT_MANAGER,
            'name' => 'Account Manager 3',
            'email' => 'AM3@telkom.com',
            'password' => 'AM345',
            'status' => 'active',
        ]);

        User::create([
            'role_id' => User::ROLE_SUPPORT_INPUTTER,
            'name' => 'Support Inputter',
            'email' => 'inputter@telkom.com',
            'password' => 'inputter123',
            'status' => 'active',
        ]);

        User::create([
            'role_id' => User::ROLE_SUPPORT_PAYCALL,
            'name' => 'Support Paycall',
            'email' => 'paycall@telkom.com',
            'password' => 'paycall123',
            'status' => 'active',
        ]);
    }
}