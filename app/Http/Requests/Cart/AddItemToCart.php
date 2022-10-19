<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddItemToCart extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => [
                'required',
                Rule::exists('products', 'sku')
            ],
            'quantity' => 'required|numeric|gt:0',
        ];
    }
}
