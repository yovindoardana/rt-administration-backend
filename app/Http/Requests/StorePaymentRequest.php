<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'resident_id'  => ['required', 'integer', 'exists:residents,id'],
            'amount'       => ['required', 'numeric'],
            'payment_date' => ['required', 'date'],
            'status'       => ['required', 'in:paid,unpaid'],
        ];
    }
}
