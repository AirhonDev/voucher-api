<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        private AuthService $service
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->service->logoutUser();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
