<?php

namespace Tests\Feature\Api\Auth;

use App\Http\Dto\User\UserDto;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Voucher;
use App\Notifications\WelcomeEmail;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_register_user_and_send_welcome_email()
    {
        Notification::fake();

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $voucherService = Mockery::mock(VoucherService::class);

        $this->app->instance(UserRepositoryInterface::class, $userRepository);
        $this->app->instance(VoucherService::class, $voucherService);

        $userDto = new UserDto(
            firstName: 'John',
            lastName: 'Doe',
            username: 'johndoe',
            email: 'johndoe@example.com',
            password: 'password'
        );

        $user = User::factory()->make([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
        ]);

        $voucher = new Voucher(['code' => 'VOUCHER123']);

        $userRepository->shouldReceive('createUser')
            ->once()
            ->with(\Mockery::on(function ($arg) use ($userDto) {
                return $arg == $userDto;
            }))
            ->andReturn($user);

        $voucherService->shouldReceive('createVoucher')
            ->once()
            ->with(\Mockery::on(function ($arg) use ($user) {
                return $arg == $user;
            }))
            ->andReturn($voucher);

        $response = $this->postJson('/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'email' => 'johndoe@example.com',
            ],
        ]);

        Notification::assertSentTo(
            [$user],
            WelcomeEmail::class,
            function ($notification, $channels) use ($voucher) {
                $reflection = new \ReflectionClass($notification);
                $property = $reflection->getProperty('voucherCode');
                $property->setAccessible(true);
                return $property->getValue($notification) === $voucher->code;
            }
        );
    }

    public function test_should_return_422_if_email_already_exists()
    {
        Notification::fake();

        // Create an existing user
        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'janedoe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_should_return_422_if_username_already_exists()
    {
        Notification::fake();

        // Create an existing user
        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'janedoe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/auth/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'janedoe',
            'email'     => 'unique@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);
    }

    public function test_should_return_422_if_no_request_body()
    {
        Notification::fake();

        User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'janedoe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/auth/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name', 'username', 'email', 'password']);
    }
}
