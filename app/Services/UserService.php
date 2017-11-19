<?php

namespace App\Services;

use App\User;

class UserService
{
    public function registerUser($mobile)
    {
        return User::create([
            'mobile' => $mobile,
            'password' => ''
        ]);
    }
}
