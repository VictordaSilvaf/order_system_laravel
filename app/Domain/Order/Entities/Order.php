<?php

final class Order
{
    private array $items = [];

    private function __construct(
        public readonly string $id,
        private OrderStatus $status
    ) {}

    public static function create(): self
    {
        return new self(
            id: uuid_create(UUID_TYPE_RANDOM),
            status: OrderStatus::DRAFT
        );
    }

    public function addItem(string $productId, int $qty, float $price): void
    {
        $this->assertNotFinalized();

        $this->items[] = new OrderItem($productId, $qty, $price);
    }

    public function removeItem(string $productId): void
    {
        $this->assertNotFinalized();

        $this->items = array_filter(
            $this->items,
            fn(OrderItem $item) => $item->productId !== $productId
        );
    }

    public function total(): float
    {
        return array_reduce(
            $this->items,
            fn($total, OrderItem $item) => $total + $item->subtotal(),
            0
        );
    }

    public function finalize(): void
    {
        if (empty($this->items)) {
            throw new DomainException('Pedido vazio');
        }

        $this->status = OrderStatus::FINALIZED;
    }

    private function assertNotFinalized(): void
    {
        if ($this->status === OrderStatus::FINALIZED) {
            throw new DomainException('Pedido já finalizado');
        }
    }
}
