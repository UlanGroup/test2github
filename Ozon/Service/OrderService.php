<?php

namespace app\Ozon\Service;

use app\Ozon\Entity\Order;
use app\Ozon\Entity\Product;

use app\Ozon\Repository\RegionRepository;

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
                if ($order->offer_id == $product->offer_id) {
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
        $orders = Order::find()->where(['or', ['cluster' => null], ['cluster' => 'Юг']])->all();

        // регионы_кластеры
        $regionR = new RegionRepository();
        $regions = $regionR->all();

        foreach ($orders as $order) {
//            if (empty($order->region) && !empty($order->city)) $order->region = $order->city;
            foreach ($regions as $region) {
                if ($order->region == $region->region) {
                    $order->cluster = $region->cluster;
                    $order->save();
                }
            }
        }
    }


    public function setStatus()
    {
        $orders = Order::find()->where(['status' => ['awaiting_packaging', 'awaiting_deliver', 'delivering', 'delivered', 'cancelled']])->all();

        foreach ($orders as $order) {
            if ($order->status == 'awaiting_packaging') $order->status = '1';
            if ($order->status == 'awaiting_deliver') $order->status = '2';
            if ($order->status == 'delivering') $order->status = '3';
            if ($order->status == 'delivered') $order->status = '4';
            if ($order->status == 'cancelled') $order->status = '5';
            $order->save();
        }
    }

    public function setTransaction($posting_number, $data)
    {
        $order = Order::find()->where(['posting_number' => $posting_number])->one();

        $order->commission = $data['result']['sale_commission'];
        $order->delivery_cost = $data['result']['processing_and_delivery'];
        $order->refund_cost = $data['result']['refunds_and_cancellations'];
        $order->save();
    }
}