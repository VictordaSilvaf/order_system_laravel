<?php

declare(strict_types=1);

namespace App\Domain\Order\Entities;

use DateTimeImmutable;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\ValueObjects\OrderId;
use App\Domain\Order\Exceptions\EmptyOrderException;

final class Order
{
    private array $items = [];
    private OrderStatus $status;
    private DateTimeImmutable $createdAt;

    private function __construct(
        public readonly OrderId $id,
    ) {
        $this->status = OrderStatus::PENDING;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function reconstruct(
        OrderId $id,
        OrderStatus $status,
        DateTimeImmutable $createdAt,
        array $items = []
    ): self {
        $order = new self($id);

        $order->status = $status;
        $order->createdAt = $createdAt;

        $order->items = $items;

        return $order;
    }

    public static function create(OrderId $id): self
    {
        return new self($id);
    }

    public function addItem(OrderItem $item): void
    {
        if ($this->status !== OrderStatus::PENDING) {
            throw new \DomainException('Não é possível adicionar itens a um pedido que não está pendente');
        }

        $this->items[] = $item;
    }

    public function approve(): void
    {
        if (empty($this->items)) {
            throw new EmptyOrderException();
        }

        $this->status = OrderStatus::APPROVED;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }
}
