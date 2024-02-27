<?php

namespace app\Ozon\Factory;

use app\Log\Entity\Error;
use app\Ozon\DTO\OrderDTO;
use app\Ozon\Entity\Order;

class OrderFactory
{

    // создать ордер
    public function create(OrderDTO $dto): ?Order
    {
        /** @var Order $order */
        $order = Order::find()->where(['order_id' => $dto->order_id, 'created_at' => $dto->created_at, 'price' => $dto->price])->one();

        if (!empty($order)) {
            $order->status = $dto->status;
            $order->save();
            return $order;
        }

        $order = new Order();
        $order->order_id = $dto->order_id;
        $order->city = $dto->city;
        $order->delivery_type = $dto->delivery_type;
        $order->is_premium = $dto->is_premium;
        $order->payment_type = $dto->payment_type;
        $order->region = $dto->region;
        $order->warehouse_name = $dto->warehouse_name;
        $order->order_number = $dto->order_number;
        $order->posting_number = $dto->posting_number;
        $order->offer_id = $dto->offer_id;
        $order->price = $dto->price;
        $order->qty = $dto->qty;
        $order->sku = $dto->sku;
        $order->created_at = $dto->created_at;
        $order->commission = $dto->commission;
        $order->delivery_cost = $dto->delivery_cost;
        $order->refund_cost = $dto->refund_cost;
        $order->status = $dto->status;
        if (!$order->save()) Error::error('$order->create', $order->getErrors());

        return $order;
    }

}
