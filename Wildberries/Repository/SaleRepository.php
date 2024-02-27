<?php

namespace app\Wildberries\Repository;

use app\Wildberries\Entity\Sale;


class SaleRepository
{

    // Все ордера
    public function all(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Sale::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('date DESC')->limit($limit)->all();
    }


    // Один ордер
    public function one(string $saleID): ?array
    {
        return Sale::find()->where(['saleID' => $saleID])->one();
    }
}
