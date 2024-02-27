<?php

namespace app\Order\Factory;

use app\Order\Entity\Stock;

class ProductStockFactory
{

    // создать запись об остатках
    public function create(array $array): ?Stock
    {
        /** @var Stock $pw */
        $pw = Stock::find()->where(['sku' => $array['sku'], 'warehouse_name' => $array['warehouse_name']])->one();
        if (!empty($pw)) {
            $pw->promised = $array['promised_amount'];
            $pw->free = $array['free_to_sell_amount'];
            $pw->save();
            return $pw;
        }

        $pw = new Stock();
        $pw->sku = $array['sku'];
        $pw->warehouse_name = $array['warehouse_name'];
        $pw->promised = $array['promised_amount'];
        $pw->free = $array['free_to_sell_amount'];
        $pw->save();

        return $pw;
    }
}
