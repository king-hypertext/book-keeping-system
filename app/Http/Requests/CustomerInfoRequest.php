<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerInfoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'phone' => 'string|required|max_digits:14',
            'card_number' => 'required|numeric|unique:customers,card_number',
            'next_of_king' => 'required|string',
            'daily_payable_amount' => 'required|integer'
        ];
    }
}
