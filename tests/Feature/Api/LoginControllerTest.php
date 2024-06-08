<?php

namespace Tests\Feature;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;
use Mockery;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_do_successful_login()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('authenticateUser')
            ->with('testuser', 'password123')
            ->andReturn($user);

        $this->app->instance(AuthService::class, $authService);

        $response = $this->postJson('/auth/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'username',
                    'email',
                    'token',
                ],
            ]);
    }

    public function test_should_throw_error_on_unsuccessful_login()
    {
        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('authenticateUser')
            ->with('wronguser', 'wrongpassword')
            ->andReturn(null);

        $this->app->instance(AuthService::class, $authService);

        $response = $this->postJson('/auth/login', [
            'username' => 'wronguser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_should_create_token_on_successful_login()
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->username = 'testuser';
        $user->password = bcrypt('password123');

        $authService = Mockery::mock(AuthService::class);
        $authService->shouldReceive('authenticateUser')
            ->with('testuser', 'password123')
            ->andReturn($user);

        $newAccessToken = Mockery::mock(NewAccessToken::class);
        $newAccessToken->plainTextToken = 'mocked-token';

        $user->shouldReceive('createToken')
            ->with('Api Token')
            ->andReturn($newAccessToken);

        $this->app->instance(AuthService::class, $authService);

        $response = $this->postJson('/auth/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['token' => 'mocked-token']);
    }
}
