<?php

namespace App\Domain\Order\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CANCELED = 'canceled';
}
