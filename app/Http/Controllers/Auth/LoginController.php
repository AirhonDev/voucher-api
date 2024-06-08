<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    const ERROR_MESSAGE = 'The provided credentials are incorrect.';
    const TOKEN_NAME = 'Api Token';

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

        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;
        $user->token = $token;

        return (new LoginResource($user))->response()->setStatusCode(200);
    }
}
