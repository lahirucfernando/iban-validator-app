<?php 

namespace App\Services;

use App\Models\User;

class TokenService
{   
    /**
     * Create a new authentication token for the given user.
     * 
     * @param User $user
     * @return string
     */
    public function createToken(User $user): string
    {
        return $user->createToken('main')->plainTextToken;
    }
}