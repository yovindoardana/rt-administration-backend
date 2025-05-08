<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
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
            'resident_id'      => ['sometimes', 'exists:residents,id'],
            'house_id'         => ['sometimes', 'exists:houses,id'],
            'fee_type'         => ['sometimes', 'in:security,cleaning'],
            'month'            => ['sometimes', 'integer', 'between:1,12'],
            'year'             => ['sometimes', 'integer', 'digits:4'],
            'duration_months'  => ['sometimes', 'integer', 'min:1'],
            'amount'           => ['sometimes', 'integer', 'min:0'],
            'status'           => ['sometimes', 'in:paid,unpaid'],
            'payment_date'     => ['sometimes', 'date'],
        ];
    }
}
