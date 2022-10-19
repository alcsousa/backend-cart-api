<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentDetailFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 20, 300);

        return [
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
            'last_4_digits' => 1234
        ];
    }
}
