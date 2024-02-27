<?php

namespace app\Order\Repository;

use app\Order\Entity\Product;


class StockRepository
{

    // Все товары
    public function all(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Product::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id DESC')->limit($limit)->all();
    }


    // Продажи
    // $period = за какое количество месяцев
    public function storage(int $period): ?array
    {
        $products = Product::find()->joinWith(['orders', 'stocks'])->orderBy('product.id DESC')->asArray()->all();

        $result = [];
        foreach ($products as $product) {
            $p = [
                'id' => $product['id'],
                'offer_id' => $product['offer_id'],
                'cluster1' => 0,
                'cluster2' => 0,
                'cluster3' => 0,
                'cluster4' => 0,
                'cluster5' => 0,
                'cluster6' => 0,
                'cluster7' => 0,
                'cluster8' => 0
            ];

            foreach ($product['orders'] as $order) {
                if ($order['created'] > date('Y-m-d 00:00:00', strtotime("-$period month"))) {
                    if ($order['cluster'] == "Москва и МО") $p['cluster1'] += $order['quantity'];
                    if ($order['cluster'] == "Центр") $p['cluster2'] += $order['quantity'];
                    if ($order['cluster'] == "Северо-Запад") $p['cluster3'] += $order['quantity'];
                    if ($order['cluster'] == "Поволжье") $p['cluster4'] += $order['quantity'];
                    if ($order['cluster'] == "Юг") $p['cluster5'] += $order['quantity'];
                    if ($order['cluster'] == "Урал") $p['cluster6'] += $order['quantity'];
                    if ($order['cluster'] == "Сибирь") $p['cluster7'] += $order['quantity'];
                    if ($order['cluster'] == "Дальний восток") $p['cluster7'] += $order['quantity'];
                }
            }

            $result[] = $p;
        }

        return $result;
    }
}
