<?php

namespace App\Http\Dto\Voucher;

class VoucherFilterDto
{
    public function __construct(
        public ?string $code,
        public ?int $perPage = 10,
        public ?int $page = 1
    ) {
    }
}
