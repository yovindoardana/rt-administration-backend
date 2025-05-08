<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method \App\Models\House|null route(string $param)
 */
class UpdateHouseRequest extends FormRequest
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
            'house_number' => ['sometimes', 'string', 'max:255', Rule::unique('houses')->ignore($this->route('house'))],
            'status'       => ['sometimes', 'in:occupied,vacant'],
        ];
    }
}
