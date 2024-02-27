<?php

namespace app\Ozon\Factory;

use Yii;
use app\Ozon\Entity\Stock;

class StockFactory
{

    // удалить все записи  об остатках
    public function clean(): void
    {
        Yii::$app->db->createCommand()->delete('ozon_stock')->execute();
    }


    // создать запись об остатках
    public function create(array $array): ?Stock
    {
        /** @var Stock $stock */
        $stock = Stock::find()->where(['offer_id' => $array['item_code'], 'warehouse_name' => $array['warehouse_name']])->one();
        if (!empty($stock)) {
            $stock->promised = $array['promised_amount'];
            $stock->free = $array['free_to_sell_amount'];
            $stock->save();
            return $stock;
        }

        $stock = new Stock();
        $stock->sku = $array['sku'];
        $stock->offer_id = $array['item_code'];
        $stock->warehouse_name = $array['warehouse_name'];
        $stock->promised = $array['promised_amount'];
        $stock->free = $array['free_to_sell_amount'];
        $stock->save();

        return $stock;
    }
}
