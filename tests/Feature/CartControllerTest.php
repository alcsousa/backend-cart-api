<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_a_cart_can_be_created()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $payload = ['user_id' => User::factory()->create()->id];

        $response = $this->post('/api/v1/carts', $payload, $this->headers);

        $response->assertCreated();
        $response->assertJsonStructure(['uuid']);
    }

    public function test_that_a_cart_can_not_be_created_without_a_valid_api_token()
    {
        $response = $this->post('/api/v1/carts', [], $this->headers);

        $response->assertUnauthorized();
    }

    public function test_that_an_item_can_be_added_to_the_cart()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        $payload = [
            'sku' => $product->getSkuCode(),
            'quantity' => 1
        ];

        $response = $this->post('/api/v1/carts/'.$cart->getCartIdentifier(), $payload, $this->headers);

        $response->assertOk();
        $response->assertJsonStructure(['message']);
    }

    public function test_that_a_cart_item_quantity_is_increased_when_a_repeated_item_is_added()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $products = Product::factory(3)->create();

        $cart->addItem($products[0], 1);
        $cart->addItem($products[1], 1);
        $cart->addItem($products[2], 1);
        $cart->addItem($products[2], 1);
        $cart->addItem($products[2], 1);

        $items = $cart->fresh()->items;

        $this->assertCount(3, $items->toArray());
    }

    public function test_that_a_cart_can_calculate_the_total_items()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $products = Product::factory(3)->create();

        $cart->addItem($products[0], 1);
        $cart->addItem($products[1], 2);
        $cart->addItem($products[2], 3);

        $this->assertEquals(6, $cart->calculateTotalItems());
    }

    public function test_that_a_cart_can_calculate_the_total_amount()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $products = Product::factory(3)->create(['price' => 100]);

        $cart->addItem($products[0], 1);
        $cart->addItem($products[1], 2);
        $cart->addItem($products[2], 3);

        $this->assertEquals(600, $cart->calculateTotalAmount());
    }

    public function test_that_the_card_items_can_be_listed()
    {
        $count = 5;
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory($count)->create(['cart_id' => $cart->id]);

        $response = $this->get('/api/v1/carts/'.$cart->getCartIdentifier(), $this->headers);

        $response->assertOk();
        $response->assertJsonCount($count, 'data.items');
        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'name',
                        'sku',
                        'description',
                        'price',
                        'quantity',
                    ]
                ],
                'total_items',
                'total_amount',
            ]
        ]);
    }
}
