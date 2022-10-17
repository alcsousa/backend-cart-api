<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Product::factory(50)->create();
    }

    public function test_if_the_products_can_be_listed()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->get('/api/v1/products', $this->headers);

        $response->assertOk();
        $response->assertJsonStructure(['data' =>
            [
                '*' => [
                    'id',
                    'name',
                    'sku',
                    'description',
                    'price'
                ]
            ]
        ]);
    }

    public function test_that_the_products_list_size_can_be_changed_with_limit()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $limit = 10;

        $response = $this->get('/api/v1/products?limit='.$limit, $this->headers);

        $response->assertOk();
        $response->assertJsonCount($limit, 'data');
    }

    public function test_that_an_unauthorized_user_should_not_access_the_products_list()
    {
        $response = $this->get('/api/v1/products', $this->headers);

        $response->assertUnauthorized();
    }
}
