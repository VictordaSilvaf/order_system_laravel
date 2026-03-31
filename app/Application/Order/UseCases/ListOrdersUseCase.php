<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repositories\OrderRepository;

class ListOrdersUseCase
{
    public function __construct(
        private OrderRepository $repository
    ) {}

    public function execute(): array
    {
        return array_map(function ($order) {
            return [
                'id' => $order->id()->value,
                'status' => $order->status()->value,
                'items' => array_map(fn($item) => [
                    'product_id' => $item->productId(),
                    'quantity' => $item->quantity(),
                ], $order->items()),
            ];
        }, $this->repository->findAll());
    }
}
