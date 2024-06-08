<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function authenticateUser(string $username, string $password)
    {
        $user = $this->userRepository->findByUsername(
            username: $username
        );

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }
}
