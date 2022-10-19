<?php

namespace App\Contracts;

interface StandardProduct
{
    public function getId(): int;
    public function getDisplayName(): string;
    public function getDescription(): string;
    public function getSkuCode(): string;
    public function getPrice(): float;
}
