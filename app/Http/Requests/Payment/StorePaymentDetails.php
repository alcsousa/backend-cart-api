<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentDetails extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_number' => 'required|numeric|digits_between:9,19',
            'card_holder' => 'required|string|max:60',
            'expiration_date' => 'required|regex:/^\d{2}\/\d{2}$/',
            'cvv_code' => 'required|numeric|digits:3',
        ];
    }
}
