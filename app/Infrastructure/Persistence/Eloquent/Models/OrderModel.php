<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Order\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

final class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'id',
        'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function status(): OrderStatus
    {
        return $this->status;
    }
}
