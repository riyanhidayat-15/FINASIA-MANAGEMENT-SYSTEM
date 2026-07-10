<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function generateOrderNumber():string
    {
        $prefix = 'ORD-' . now()->format('Ym');

        $lastOrder = Order::query()
            ->where('order_number', 'like', "{$prefix}-%")
            ->latest('id')
            ->first();

        $sequence = 1;

        if ($lastOrder) {
            $sequence = ((int) substr($lastOrder->order_number, -3)) + 1;
        }

        return sprintf(
            '%s-%03d',
            $prefix,
            $sequence
        );
    }

    public function create(array $data): Order {

        $data['order_number'] = $this->generateOrderNumber();

        return Order::create($data);

    }
}