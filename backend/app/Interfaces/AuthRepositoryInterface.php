<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Register new user 
     * 
     * @param array $data
     * @return User
     */
    public function register(array $data);

}
