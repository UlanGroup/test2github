<?php

namespace app\Wildberries\Repository;

use app\Wildberries\Entity\Order;


class OrderRepository
{

    // Все ордера
    public function all(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Order::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('date DESC')->limit($limit)->all();
    }


    // Один ордер
    public function one(string $gNumber): ?array
    {
        return Order::find()->where(['gNumber' => $gNumber])->one();
    }
}
