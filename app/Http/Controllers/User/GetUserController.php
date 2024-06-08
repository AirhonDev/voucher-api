<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class GetUserController extends Controller
{

    public function __invoke(Request $request)
    {
        $user = auth()->user();

        return (new UserResource($user))->response()->setStatusCode(200);
    }
}
