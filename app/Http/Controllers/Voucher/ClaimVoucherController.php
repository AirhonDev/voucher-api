<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\ClaimVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;

class ClaimVoucherController extends Controller
{
    public function __construct(
        private VoucherService $voucherService
    ) {
    }

    public function __invoke(ClaimVoucherRequest $request)
    {
        $voucher = $this->voucherService->claimVoucher($request->code);

        return (new VoucherResource($voucher))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
