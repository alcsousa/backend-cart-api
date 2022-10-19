<?php

namespace App\Models;

use App\Contracts\StandardProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements StandardProduct
{
    use HasFactory;

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getDisplayName(): string
    {
        return $this->attributes['name'];
    }

    public function getDescription(): string
    {
        return $this->attributes['description'];
    }

    public function getSkuCode(): string
    {
        return $this->attributes['sku'];
    }

    public function getPrice(): float
    {
        return $this->attributes['price'];
    }
}
