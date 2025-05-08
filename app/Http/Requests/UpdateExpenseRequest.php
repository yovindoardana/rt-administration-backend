<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'description' => ['sometimes', 'string', 'max:255'],
            'amount'      => ['sometimes', 'integer', 'min:0'],
            'date'        => ['sometimes', 'date'],
            'type'        => ['sometimes', 'in:recurring,one-time'],
            'category'    => ['nullable', 'string', 'max:100'],
        ];
    }
}
