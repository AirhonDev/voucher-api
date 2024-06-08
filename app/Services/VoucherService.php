<?php

namespace App\Services;

use App\Http\Dto\Voucher\CreateVoucherDto;
use App\Http\Dto\Voucher\UpdateVoucherDto;
use App\Models\User;
use App\Models\Voucher;
use App\Repositories\Voucher\VoucherRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VoucherService
{
    public function __construct(
        private VoucherRepositoryInterface $voucherRepository
    ) {
    }

    public function createVoucher(
        User $user,
        ?string $code = null,
        ?Carbon $expiresAt = null
    ): Voucher {
        $voucher = new CreateVoucherDto(
            userId: $user->id,
            code: $code ?? $this->generateUniqueCode(),
            expiresAt: $expiresAt ?? Carbon::now()->addDays(7)
        );

        return $this->voucherRepository->createVoucher(
            voucher: $voucher
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

    public function claimVoucher(string $code): Voucher
    {
        $voucher = $this->voucherRepository->findByCode($code);

        $this->voucherRepository->updateVoucher($voucher, new UpdateVoucherDto(
            claimedAt: Carbon::now()
        ));

        return $voucher;
    }
}
