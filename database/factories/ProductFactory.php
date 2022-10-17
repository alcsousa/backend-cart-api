<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucwords($this->faker->words(3, true)),
            'sku' => mb_strtoupper($this->faker->lexify('PDT????????')),
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 20, 300),
        ];
    }
}
