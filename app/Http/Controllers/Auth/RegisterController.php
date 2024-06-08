<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Dto\User\UserDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Notifications\WelcomeEmail;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VoucherService $voucherService
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

        $voucher = $this->voucherService->createVoucher(
            user: $user
        );

        $user->notify(new WelcomeEmail($voucher->code));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
