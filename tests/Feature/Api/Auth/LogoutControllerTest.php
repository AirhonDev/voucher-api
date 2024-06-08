<?php

namespace Tests\Feature\Api\Auth;

use App\Models\Sanctum\PersonalAccessToken;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_logout_user()
    {
        $user = Mockery::mock(Authenticatable::class);
        $token = Mockery::mock(PersonalAccessToken::class);

        Auth::shouldReceive('guard')->andReturnSelf();
        Auth::shouldReceive('user')->once()->andReturn($user);
        Auth::shouldReceive('setUser')->andReturnSelf();
        Auth::shouldReceive('shouldUse')->andReturnSelf();
        Auth::shouldReceive('check')->andReturn(true);

        $user->shouldReceive('currentAccessToken')->once()->andReturn($token);

        $token->shouldReceive('delete')->once();

        $authService = new AuthService(Mockery::mock(UserRepositoryInterface::class));
        $this->app->instance(AuthService::class, $authService);

        $response = $this->actingAs($user)->postJson('/auth/logout');

        $response->assertStatus(204);
    }
}
