<?php

namespace app\Ozon\Service;

use app\Ozon\Repository\WarehouseRepository;
use app\Ozon\Entity\Product;
use app\Ozon\Entity\Stock;

// Сервис товара
class ProductService
{

    // привязать product_id к остаткам
    public function setProductId()
    {
        // остатки
        $stocks = Stock::find()->where(['product_id' => null])->all();

        // товары
        $products = Product::find()->all();

        foreach ($stocks as $stock) {
            foreach ($products as $product) {
                if ($product->offer_id == $stock->offer_id) {
                    $stock->product_id = $product->id;
                    $stock->save();
                }
            }
        }
    }


    // привязать cluster к остаткам
    public function setCluster()
    {
        // остатки
        $stocks = Stock::find()->where(['or', ['cluster' => null], ['cluster' => 'Юг']])->all();

        // регионы_кластеры
        $warehouseR = new WarehouseRepository();
        $warehouses = $warehouseR->all();

        foreach ($stocks as $stock) {
            foreach ($warehouses as $warehouse) {
                if ($warehouse->warehouse == $stock->warehouse_name) {
                    $stock->cluster = $warehouse->cluster;
                    $stock->save();
                }
            }
        }
    }

}