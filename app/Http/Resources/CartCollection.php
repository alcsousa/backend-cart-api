<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'items' => CartItemCollection::collection($this['items']),
            'total_items' => $this['total_items'],
            'total_amount' => $this['total_amount'],
        ];
    }
}
