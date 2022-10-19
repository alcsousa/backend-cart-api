<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => User::factory()->create()->id,
            'payment_detail_id' => null,
            'checked_out_at' => null
        ];
    }
}
