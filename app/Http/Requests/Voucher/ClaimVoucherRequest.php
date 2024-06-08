<?php

namespace App\Http\Requests\Voucher;

use App\Repositories\Voucher\VoucherRepositoryInterface;
use App\Rules\VoucherNotClaimed;
use Illuminate\Foundation\Http\FormRequest;

class ClaimVoucherRequest extends FormRequest
{
    public function __construct(
        protected VoucherRepositoryInterface $voucherRepository
    ) {
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'exists:vouchers,code', new VoucherNotClaimed($this->voucherRepository)],
        ];
    }
}
