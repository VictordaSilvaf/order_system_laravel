<?php

declare(strict_types=1);

namespace App\Domain\Order\ValueObjects;

use Ramsey\Uuid\Uuid;

final class ProductId
{
    public function __construct(
        public readonly string $value
    ) {}

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
