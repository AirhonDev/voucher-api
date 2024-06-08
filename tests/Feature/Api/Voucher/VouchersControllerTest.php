<?php

namespace Tests\Feature\Api\Voucher;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VouchersControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate using Sanctum
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_should_return_collection_of_vouchers_with_pagination()
    {
        Voucher::factory()->count(10)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/vouchers?page=1&per_page=10&code=TESTCODE');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'expires_at',
                        'claimed_at',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'per_page',
                    'total',
                ],
            ]);
    }

    public function test_should_filter_vouchers_by_code()
    {
        Voucher::factory()->create(['user_id' => $this->user->id, 'code' => 'FILTERCODE']);
        Voucher::factory()->count(9)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/vouchers?page=1&per_page=10&code=FILTERCODE');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'expires_at',
                        'claimed_at',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'per_page',
                    'total',
                ],
            ]);

        $response->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'FILTERCODE');
    }

    public function test_should_create_voucher()
    {
        $payload = [
            'code' => 'NEWCODE',
            'expires_at' => Carbon::now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->postJson('/vouchers', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'code',
                    'expires_at',
                    'claimed_at',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $this->assertDatabaseHas('vouchers', [
            'code' => 'NEWCODE',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_should_create_voucher_with_defaults()
    {
        $payload = [];

        $response = $this->postJson('/vouchers', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'code',
                    'expires_at',
                    'claimed_at',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $this->assertDatabaseHas('vouchers', [
            'user_id' => $this->user->id,
        ]);

        $voucher = Voucher::where('user_id', $this->user->id)->first();

        $this->assertNotNull($voucher->code);
        $this->assertNotNull($voucher->expires_at);
    }
}
