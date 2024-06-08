<?php

namespace App\Repositories\Voucher;

use App\Http\Dto\Voucher\CreateVoucherDto;
use App\Http\Dto\Voucher\UpdateVoucherDto;
use App\Http\Dto\Voucher\VoucherFilterDto;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VoucherRepository implements VoucherRepositoryInterface
{

    /**
     * Create a new voucher for a user.
     *
     * @param User $user The user for whom the voucher is being created.
     * @param string $code The code of the voucher.
     * @return Voucher The created voucher.
     */
    public function createVoucher(CreateVoucherDto $voucher): Voucher
    {
        return Voucher::create($voucher->toArray());
    }

    /**
     * Find a voucher by its code.
     *
     * @param string $code The code of the voucher.
     * @return Voucher|null The voucher if found, null otherwise.
     */
    public function findByCode(string $code): ?Voucher
    {
        return Voucher::where('code', $code)->first();
    }

    /**
     * Count the number of vouchers associated with a given user.
     *
     * @param User $user The user whose vouchers are being counted.
     * @return int The number of vouchers associated with the user.
     */
    public function countUserVouchers(User $user): int
    {
        return $user->vouchers()->count();
    }

    /**
     * Find vouchers by user.
     *
     * @param User $user The user whose vouchers are being retrieved.
     * @return Collection The collection of vouchers for the user.
     */
    public function getUserVouchers(User $user, VoucherFilterDto $voucherFilterDto): LengthAwarePaginator
    {
        $query = $user->vouchers();

        if ($voucherFilterDto->code) {
            $query->where('code', $voucherFilterDto->code);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($voucherFilterDto->perPage, ['*'], 'page', $voucherFilterDto->page);
    }

    /**
     * Update an existing voucher.
     *
     * @param Voucher $voucher The voucher instance to be updated.
     * @param UpdateVoucherDto $updateVoucherDto The data transfer object containing the updated voucher information.
     * @return bool True if the voucher was successfully updated, false otherwise.
     */
    public function updateVoucher(Voucher $voucher, UpdateVoucherDto $updateVoucherDto): bool
    {
        return $voucher->update($updateVoucherDto->toArray());
    }
}
