<?php

namespace App\Repositories\Voucher;

use App\Models\User;
use App\Models\Voucher;

interface VoucherRepositoryInterface
{
    /**
     * Create a new voucher for the given user.
     *
     * @param User $user The user for whom the voucher is being created.
     * @param string $code The unique code for the voucher.
     * @return Voucher The created voucher instance.
     */
    public function createVoucher(User $user, string $code): Voucher;

    /**
     * Find a voucher by its code.
     *
     * @param string $code The unique code of the voucher.
     * @return Voucher|null The voucher instance if found, or null if not found.
     */
    public function findByCode(string $code): ?Voucher;
}
