<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    const ERROR_MESSAGE = 'The provided credentials are incorrect.';

    public function __construct(
        private AuthService $service
    ) {
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->service->authenticateUser(
            username: $request->username,
            password: $request->password
        );

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => [self::ERROR_MESSAGE],
            ]);
        }

        return (new LoginResource($user))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
