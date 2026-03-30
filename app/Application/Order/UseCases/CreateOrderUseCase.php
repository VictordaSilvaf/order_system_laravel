<?php

declare(strict_types=1);

namespace App\Application\Order\UseCases;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderItem;
use App\Domain\Order\Repositories\OrderRepository;
use App\Domain\Order\ValueObjects\OrderId;
use App\Domain\Order\ValueObjects\ProductId;

final class CreateOrderUseCase
{
    public function __construct(
        private readonly OrderRepository $repository
    ) {}

    public function execute(array $items): string
    {
        $order = Order::create(OrderId::generate());

        foreach ($items as $item) {
            $order->addItem(
                new OrderItem(
                    new ProductId($item['product_id']),
                    $item['quantity']
                )
            );
        }

        $order->approve();

        $this->repository->save($order);

        return $order->id()->value;
    }
}
