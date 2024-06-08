<?php

namespace App\Services;

use App\Models\User;
use App\Models\Voucher;
use App\Repositories\Voucher\VoucherRepositoryInterface;
use Illuminate\Support\Str;

class VoucherService
{
    public function __construct(
        private VoucherRepositoryInterface $voucherRepository
    ) {
    }

    public function createVoucher(User $user): Voucher
    {
        $code = $this->generateUniqueCode();
        return $this->voucherRepository->createVoucher(
            user: $user,
            code: $code
        );
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(5));
        } while ($this->voucherRepository->findByCode(
            code: $code
        ));

        return $code;
    }
}
