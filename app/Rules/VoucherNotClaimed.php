<?php

namespace App\Rules;

use App\Repositories\Voucher\VoucherRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class VoucherNotClaimed implements ValidationRule
{
    private const ERROR_MESSAGE = 'The voucher has already been claimed.';

    public function __construct(
        private VoucherRepositoryInterface $voucherRepository
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        $voucher = $this->voucherRepository->findByCode($value);

        if ($voucher->claimed_at) {
            $fail(self::ERROR_MESSAGE);
        }
    }
}
