<?php

namespace App\Repositories\User;

use App\Http\Dto\User\UserDto;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $username
     * 
     * @return User
     */
    public function findByUsername(string $username): ?User;

    /**
     * Create a new user.
     *
     * @param UserDto $userDto Data transfer object containing user information.
     * 
     * @return User The created user instance.
     */
    public function createUser(UserDto $userDto): User;
}
