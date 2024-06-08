<?php

namespace App\Http\Dto\Voucher;

use Carbon\Carbon;

class CreateVoucherDto
{
    public function __construct(
        private string $userId,
        private string $code,
        private ?Carbon $expiresAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'code' => $this->code,
            'expires_at' => $this->expiresAt?->toDateTimeString(),
        ];
    }
}
