<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\ValueObjects\OrderId;
use App\Domain\Order\Repositories\OrderRepository as OrderRepositoryInterface;
use App\Models\Order as OrderModel;
use App\Models\OrderItem as OrderItemModel;

final class EloquentOrderRepository implements OrderRepositoryInterface
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

        $order = Order::reconstruct($id, $orderModel->status);

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
}
