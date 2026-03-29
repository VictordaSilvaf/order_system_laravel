<?php

final class OrderItem
{
    public function __construct(
        public readonly string $productId,
        public readonly int $quantity,
        public readonly float $price
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException();
        }

        if ($price <= 0) {
            throw new InvalidArgumentException();
        }
    }

    public function subtotal(): float
    {
        return $this->quantity * $this->price;
    }
}
