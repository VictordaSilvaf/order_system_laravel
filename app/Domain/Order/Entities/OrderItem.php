<?php

declare(strict_types=1);

namespace App\Domain\Order\Entities;

use App\Domain\Order\ValueObjects\ProductId;

final class OrderItem
{
    public function __construct(
        private readonly ProductId $productId,
        private readonly int $quantity
    ) {}

    // getter para ProductId
    public function productId(): ProductId
    {
        return $this->productId;
    }

    // getter para quantidade
    public function quantity(): int
    {
        return $this->quantity;
    }
}
