<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartItemCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'name' => $this['product']['name'],
            'sku' => $this['product']['sku'],
            'description' => $this['product']['description'],
            'price' => $this['price'],
            'quantity' => $this['quantity'],
        ];
    }
}
