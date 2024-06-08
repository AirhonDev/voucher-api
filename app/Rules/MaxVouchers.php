<?php

namespace App\Rules;

use App\Repositories\Voucher\VoucherRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class MaxVouchers implements ValidationRule
{
    /**
     * The maximum number of vouchers that a user can generate.
     */
    private const MAX_VOUCHERS = 10;

    private const ERROR_MESSAGE = 'You cannot generate more than 10 vouchers.';

    public function __construct(
        private VoucherRepositoryInterface $voucherRepository
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        $voucherCount = $this->voucherRepository->countUserVouchers($user);

        if ($voucherCount >= self::MAX_VOUCHERS) {
            $fail(self::ERROR_MESSAGE);
        }
    }
}
