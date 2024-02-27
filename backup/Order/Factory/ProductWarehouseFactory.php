<?php

namespace app\Order\Factory;

use app\Order\Entity\ProductWarehouse;

class ProductWarehouseFactory
{

    // создать запись об остатках
    public function create(array $array): ?ProductWarehouse
    {
        /** @var ProductWarehouse $pw */
        $pw = ProductWarehouse::find()->where(['product_id' => $array['sku'], 'warehouse_name' => $array['warehouse_name']])->one();
        if (!empty($pw)) {
            $pw->promised = $array['promised_amount'];
            $pw->free = $array['free_to_sell_amount'];
            $pw->save();
            return $pw;
        }

        $pw = new ProductWarehouse();
        $pw->product_id = $array['sku'];
        $pw->warehouse_name = $array['warehouse_name'];
        $pw->promised = $array['promised_amount'];
        $pw->free = $array['free_to_sell_amount'];
        $pw->save();

        return $pw;
    }
}
