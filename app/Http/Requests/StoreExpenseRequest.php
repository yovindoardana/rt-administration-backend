<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'amount'      => ['required', 'integer', 'min:0'],
            'date'        => ['required', 'date'],
            'type'        => ['required', 'in:recurring,one-time'],
            'category'    => ['nullable', 'string', 'max:100'],
        ];
    }
}
