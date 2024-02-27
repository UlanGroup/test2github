<?php

namespace app\Ozon\Repository;

use app\Ozon\Entity\Order;


class OrderRepository
{

    // Все ордера
    public function all(bool $asArray = false, $limit = 10000): ?array
    {

        $q = Order::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('created_at DESC')->limit($limit)->all();
    }


    // доставлено
    public function delivered(bool $asArray): ?array
    {
        $q = Order::find()->where(['status' => 4]);

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('created DESC')->all();
    }


    // Один ордер
    public function one(int $id): ?array
    {
        return Order::find()->where(['id' => $id])->one();
    }
}
