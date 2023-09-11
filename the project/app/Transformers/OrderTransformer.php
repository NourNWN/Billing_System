<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'id'=>(int) $order->id,
            'product'=>$order->product,
            'quantity'=>$order->quantity,
            'price_one'=>$order->price_one,
            'total'=>$order->total,
        ];
    }
}
