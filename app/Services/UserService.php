<?php

namespace App\Services;

use App\User;
use Hash;

class UserService
{
    public function registerUser($mobile)
    {
        // Set random password, although we will only use passwords if user is admin
        return User::create([
            'mobile' => $mobile,
            'password' => Hash::make(str_random(10))
        ]);
    }

    public function activateUser(User $user)
    {
        $user->is_active = 1;
        $user->save();
    }
}
