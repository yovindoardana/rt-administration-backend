<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
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
            'full_name'        => ['required', 'string', 'max:255'],
            'id_card'        => ['nullable', 'file', 'image', 'max:2048'],
            'residency_status' => ['required', 'in:permanent,contract'],
            'phone_number'     => ['required', 'string', 'max:20'],
            'marital_status'   => ['required', 'in:married,single'],
        ];
    }
}
