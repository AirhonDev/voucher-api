<?php

namespace Tests\Feature\Api\Voucher;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClaimVoucherControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_should_claim_voucher()
    {
        $voucher = Voucher::factory()->create([
            'user_id' => $this->user->id,
            'code' => 'CLAIMCODE',
            'expires_at' => Carbon::now()->addDays(10),
        ]);

        $response = $this->postJson('/vouchers/claim', ['code' => 'CLAIMCODE']);

        $response->assertStatus(200)
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
            'id' => $voucher->id,
            'claimed_at' => now(),
        ]);
    }

    public function test_should_not_claim_voucher_twice()
    {
        $voucher = Voucher::factory()->create([
            'user_id' => $this->user->id,
            'code' => 'ALREADYCLAIMED',
            'claimed_at' => now(),
            'expires_at' => Carbon::now()->addDays(10),
        ]);

        $response = $this->postJson('/vouchers/claim', ['code' => 'ALREADYCLAIMED']);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'code' => []
                ]
            ]);

        $this->assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'claimed_at' => now(),
        ]);
    }
}
