<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory()->create(),
            'product_id' => Product::factory()->create(),
            'quantity' => 1,
            'price' => $this->faker->randomFloat(2, 100, 200)
        ];
    }
}
