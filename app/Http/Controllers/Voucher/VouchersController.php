<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Dto\Voucher\VoucherFilterDto;
use App\Http\Requests\Voucher\CreateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use App\Repositories\Voucher\VoucherRepositoryInterface;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VouchersController extends Controller
{
    public function __construct(
        private VoucherRepositoryInterface $voucherRepository,
        private VoucherService $voucherService
    ) {
    }

    public function index(Request $request)
    {
        $voucherFilterDto = new VoucherFilterDto(
            page: $request->page,
            perPage: $request->per_page,
            code: $request->code
        );

        $vouchers = $this->voucherRepository
            ->getUserVouchers(Auth::user(), $voucherFilterDto);

        return VoucherResource::collection($vouchers);
    }

    public function store(CreateVoucherRequest $request)
    {
        $voucher = $this->voucherService
            ->createVoucher(
                user: Auth::user(),
                code: $request->code,
                expiresAt: new Carbon($request->expires_at)
            );

        return new VoucherResource($voucher);
    }

    public function show(Voucher $voucher)
    {
        return new VoucherResource($voucher);
    }
}
