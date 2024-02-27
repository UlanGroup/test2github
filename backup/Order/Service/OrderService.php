<?php

namespace app\Order\Service;

use app\Order\Entity\Order;
use app\Order\Entity\Product;
use app\Order\Repository\ClusterRegionRepository;

// Сервис для изменения заказов
class OrderService
{

    // привязать product_id к заказам
    public function setProductId()
    {
        // заказы
        $orders = Order::find()->where(['product_id' => null])->all();

        // товары
        $products = Product::find()->all();

        foreach ($orders as $order) {
            foreach ($products as $product) {
                if ($product->offer_id == $order->offer_id) {
                    $order->product_id = $product->id;
                    $order->save();
                }
            }
        }
    }


    // привязать cluster к заказам
    public function setCluster()
    {
        // заказы
        $orders = Order::find()->where(['cluster' => null])->all();

        // кластеры_регионы
        $crR = new ClusterRegionRepository();
        $clusters = $crR->all();

        foreach ($orders as $order) {
            foreach ($clusters as $cluster) {
                if ($cluster->region == $order->region) {
                    $order->cluster = $cluster->cluster;
                    $order->save();
                }
            }
        }
    }


}