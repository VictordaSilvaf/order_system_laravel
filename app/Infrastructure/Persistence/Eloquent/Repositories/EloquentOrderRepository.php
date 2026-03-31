<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Repositories\OrderRepository;
use App\Domain\Order\ValueObjects\OrderId;
use App\Models\Order as OrderModel;
use App\Models\OrderItem as OrderItemModel;

final class EloquentOrderRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        // salva o order
        OrderModel::updateOrCreate(
            ['id' => $order->id()->value],
            [
                'status' => $order->status()->value,
            ]
        );

        // salva os itens
        foreach ($order->items() as $item) {
            OrderItemModel::updateOrCreate(
                [
                    'order_id' => $order->id()->value,
                    'product_id' => $item->productId()->value,
                ],
                [
                    'quantity' => $item->quantity(),
                ]
            );
        }
    }

    public function findById(OrderId $id): ?Order
    {
        $orderModel = OrderModel::with('items')->find($id->value);

        if (!$orderModel) {
            return null;
        }

        $order = Order::reconstruct($id, $orderModel->status, $orderModel->created_at, []);

        foreach ($order->items() as $item) {
            OrderItemModel::updateOrCreate(
                [
                    'order_id' => $order->id()->value,
                    'product_id' => $item->productId()->value,
                ],
                [
                    'quantity' => $item->quantity(),
                ]
            );
        }

        return $order;
    }

    public function findAll(): array
    {
        $orders = OrderModel::with('items')->get();

        return $orders->map(function ($order) {
            return $this->toDomain($order);
        })->toArray();
    }

    private function toDomain(OrderModel $order): Order
    {
        return Order::reconstruct(
            id: $order->id,
            status: $order->status,
            items: $order->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ])->toArray(),
            createdAt: $order->created_at
        );
    }
}
