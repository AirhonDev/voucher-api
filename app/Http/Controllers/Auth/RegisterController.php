<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\UserDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(RegisterRequest $request)
    {
        $userDto = new UserDto(
            firstName: $request->first_name,
            lastName: $request->last_name,
            username: $request->username,
            email: $request->email,
            password: $request->password
        );

        $user = $this->userRepository->createUser($userDto);

        return new UserResource($user);
    }
}
