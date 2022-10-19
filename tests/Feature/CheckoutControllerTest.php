<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_that_a_cart_can_be_checkout_out()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $credit_card = $this->faker->creditCardDetails;

        $payload = [
            'card_number' => $credit_card['number'],
            'card_holder' => $credit_card['name'],
            'expiration_date' => $credit_card['expirationDate'],
            'cvv_code' => '123',
        ];

        $response = $this->post('/api/v1/carts/'.$cart->getCartIdentifier().'/checkout', $payload, $this->headers);

        $response->assertOk();
        $response->assertJson(['message' => 'Checkout finished successfully']);
    }
}
