<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    const TOKEN_NAME = 'Api Token';

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

        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        $user->token = $token;

        return $user;
    }
}
