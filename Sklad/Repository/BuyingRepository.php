<?php

namespace app\Sklad\Repository;

use app\Sklad\Entity\Buying;


class BuyingRepository
{

    // Все закупки
    public function all(bool $asArray = false): ?array
    {
        $q = Buying::find()->select(['supplier.name as supplier_name', 'buying.*'])
            ->where(['buying.del' => 0])
            ->join('LEFT JOIN', 'supplier', 'supplier.id = buying.supplier_id')
            ->joinWith(['buyingProducts', 'buyingProducts.product']);

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('buying.created DESC')->all();
    }


    // Одина закупка
    public function one(int $id): ?Buying
    {
        /** @var Buying */
        return Buying::find()->where(['id' => $id])->one();
    }


    // Одина закупка
    public function oneArray(int $id): ?array
    {
        return Buying::find()->where(['buying.id' => $id])->joinWith(['buyingProducts', 'buyingProducts.product'])->asArray()->one();
    }
}
