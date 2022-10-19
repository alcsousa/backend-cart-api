<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    public function addItemToCart(string $uuid, array $payload): bool
    {
        $cart = Cart::userCartByUuid($uuid)->firstOrFail();
        $product = Product::where('sku', $payload['sku'])->first();

        return $cart->addItem($product, $payload['quantity']);
    }

    public function viewCart(string $uuid): array
    {
        $cart = Cart::userCartByUuid($uuid)->firstOrFail();

        return [
            'data' => [
                'items' => $cart->items->toArray(),
                'total_items' => $cart->calculateTotalItems(),
                'total_amount' => $cart->calculateTotalAmount(),
            ]
        ];
    }
}
