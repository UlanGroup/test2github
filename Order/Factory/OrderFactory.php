<?php

namespace app\Order\Factory;

use Yii;

use app\Order\DTO\OrderDTO;
use app\Order\Entity\Order;

class OrderFactory
{

    // создать ордер
    public function create(OrderDTO $dto): ?Order
    {
        /** @var Order $order */
        $order = Order::find()->where(['offer_id' => $dto->offer_id, 'created' => $dto->created])->one();
        if (!empty($order)) {
            $order->status = $dto->status;
            $order->save();
            return $order;
        }

        $order = new Order();
        $order->market_order_id = $dto->market_order_id;
        $order->offer_id = $dto->offer_id;
        $order->price = $dto->price;
        $order->quantity = $dto->quantity;
        $order->region = $dto->region;
        $order->warehouse = $dto->warehouse;
        $order->created = $dto->created;
        $order->status = $dto->status;
        $order->save();

        return $order;
    }


    public function update(OrderDTO|Order $dto): ?Order
    {

        $order = Order::find()->where(['account_id' => Yii::$app->user->identity->account_id, 'id' => $dto->id])->one();
        if (empty($order)) {
            return null;
        }

        $order->save();

        return $order;
    }

}
