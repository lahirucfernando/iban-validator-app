<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;
use App\Exceptions\AuthenticationFailedException;

class AuthService
{   
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {}

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

    /**
     * Validate user credentials (email and password).
     *
     * @param string $email
     * @param string $password
     * @return User
     * @throws AuthenticationFailedException
     */
    public function validateCredentials(string $email, string $password): User
    {
        $user = $this->authRepository->findUserByEmail($email);

        // Check if user exists and password is correct
        if (!$user || !Hash::check($password, $user->password)) {
            throw new AuthenticationFailedException();
        }

        return $user;
    }
}