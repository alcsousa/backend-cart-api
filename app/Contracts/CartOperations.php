<?php

namespace App\Contracts;

use App\Models\Product;

interface CartOperations
{
    public function getCartIdentifier(): string;
    public function isCheckedOut(): bool;
    public function addItem(Product $product, int $quantity): bool;
    public function calculateTotalItems(): int;
    public function calculateTotalAmount(): float;
}
