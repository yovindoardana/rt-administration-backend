<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentRequest extends FormRequest
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
            'full_name'        => ['sometimes', 'string', 'max:255'],
            'id_card'        => ['sometimes', 'nullable', 'file', 'image', 'max:2048'],
            'residency_status' => ['sometimes', 'in:permanent,contract'],
            'phone_number'     => ['sometimes', 'string', 'max:20'],
            'marital_status'   => ['sometimes', 'in:married,single'],
        ];
    }
}
