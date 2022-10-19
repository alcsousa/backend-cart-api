<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addItemToCart(string $uuid, array $payload): bool
    {
        $cart = $this->getCartByidentifier($uuid);
        $product = Product::where('sku', $payload['sku'])->first();

        return $cart->addItem($product, $payload['quantity']);
    }

    public function viewCart(string $uuid): array
    {
        $cart = $this->getCartByidentifier($uuid);

        return [
            'data' => [
                'items' => $cart->items->toArray(),
                'total_items' => $cart->calculateTotalItems(),
                'total_amount' => $cart->calculateTotalAmount(),
            ]
        ];
    }

    private function getCartByidentifier(string $uuid)
    {
        return Cart::where([
            ['uuid', '=', $uuid],
            ['user_id', '=', Auth::user()->id]
        ])->firstOrFail();
    }
}
