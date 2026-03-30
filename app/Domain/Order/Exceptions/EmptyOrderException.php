<?php

declare(strict_types=1);

namespace App\Domain\Order\Exceptions;

use DomainException;

final class EmptyOrderException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot approve an order without items.');
    }
}
