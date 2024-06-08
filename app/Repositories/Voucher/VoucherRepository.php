<?php

namespace App\Repositories\Voucher;

use App\Models\User;
use App\Models\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{

    public function createVoucher(User $user, string $code): Voucher
    {
        return Voucher::create([
            'user_id' => $user->id,
            'code' => $code,
            'claimed_at' => null,
        ]);
    }

    public function findByCode(string $code): ?Voucher
    {
        return Voucher::where('code', $code)->first();
    }
}
