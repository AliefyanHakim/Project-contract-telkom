<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create new user';

    public function handle()
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        $role = $this->choice(
            'Role',
            ['Manager', 'Account Manager', 'Support Inputter', 'Support Paycall']
        );

        $roleId = match ($role) {
            'Manager' => 1,
            'Account Manager' => 2,
            'Support Inputter' => 3,
            'Support Paycall' => 4,
        };

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $roleId,
            'status' => 'active',
        ]);

        $this->info('User berhasil dibuat.');
    }
}