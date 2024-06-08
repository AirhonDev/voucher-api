<?php

namespace App\Http\Dto\Voucher;

use Carbon\Carbon;

class UpdateVoucherDto
{
    public function __construct(
        private ?Carbon $claimedAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'claimed_at' => $this->claimedAt?->toDateTimeString(),
        ];
    }
}
