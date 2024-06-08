<?php

namespace App\Http\Requests\Voucher;

use App\Repositories\Voucher\VoucherRepositoryInterface;
use App\Rules\MaxVouchers;
use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
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
            'code' => ['nullable', 'string', new MaxVouchers($this->voucherRepository)],
            'expires_at' => ['nullable', 'date']
        ];
    }
}
