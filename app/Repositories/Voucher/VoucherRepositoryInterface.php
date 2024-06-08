<?php

namespace App\Repositories\Voucher;

use App\Http\Dto\Voucher\CreateVoucherDto;
use App\Http\Dto\Voucher\UpdateVoucherDto;
use App\Http\Dto\Voucher\VoucherFilterDto;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VoucherRepositoryInterface
{
    /**
     * Create a new voucher for the given user.
     *
     * @param User $user The user for whom the voucher is being created.
     * @param string $code The unique code for the voucher.
     * @return Voucher The created voucher instance.
     */
    public function createVoucher(CreateVoucherDto $voucher): Voucher;

    /**
     * Find a voucher by its code.
     *
     * @param string $code The unique code of the voucher.
     * @return Voucher|null The voucher instance if found, or null if not found.
     */
    public function findByCode(string $code): ?Voucher;


    /**
     * Count the number of vouchers associated with a given user.
     *
     * @param User $user The user whose vouchers are being counted.
     * @return int The number of vouchers associated with the user.
     */
    public function countUserVouchers(User $user): int;

    /**
     * Find vouchers by user.
     *
     * @param User $user The user whose vouchers are being retrieved.
     * @param VoucherFilterDto $voucherFilterDto The filter options for the vouchers.
     * @return LengthAwarePaginator The paginated collection of vouchers associated with the user.
     */
    public function getUserVouchers(User $user, VoucherFilterDto $voucherFilterDto): LengthAwarePaginator;

    /**
     * Update an existing voucher.
     *
     * @param Voucher $voucher The voucher instance to be updated.
     * @param UpdateVoucherDto $updateVoucherDto The data transfer object containing the updated voucher information.
     * @return bool True if the voucher was successfully updated, false otherwise.
     */
    public function updateVoucher(Voucher $voucher, UpdateVoucherDto $updateVoucherDto): bool;
}
