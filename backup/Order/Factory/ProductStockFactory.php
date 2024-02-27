<?php

namespace app\Order\Factory;

use app\Order\Entity\ProductStock;

class ProductStockFactory
{

    // создать запись об остатках
    public function create(array $array): ?ProductStock
    {
        /** @var ProductStock $pw */
        $pw = ProductStock::find()->where(['sku' => $array['sku'], 'warehouse_name' => $array['warehouse_name']])->one();
        if (!empty($pw)) {
            $pw->promised = $array['promised_amount'];
            $pw->free = $array['free_to_sell_amount'];
            $pw->save();
            return $pw;
        }

        $pw = new ProductStock();
        $pw->sku = $array['sku'];
        $pw->warehouse_name = $array['warehouse_name'];
        $pw->promised = $array['promised_amount'];
        $pw->free = $array['free_to_sell_amount'];
        $pw->save();

        return $pw;
    }
}
