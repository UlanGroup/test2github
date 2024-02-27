<?php

namespace app\Ozon\Repository;

use app\Ozon\Entity\Product;

class ProductRepository
{

    // Все товары
    public function all(bool $asArray = false, $limit = 10000): ?array
    {
        $q = Product::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id DESC')->limit($limit)->all();
    }


    // остатки по кластерам
    public function cluster(): ?array
    {
        $products = Product::find()->where(['ozon_product.status' => 1])->joinWith(['stocks', 'sell1', 'sell3'])->orderBy('ozon_product.id DESC')->asArray()->all();

        $result = [];
        foreach ($products as $product) {
            $p = [
                'id' => $product['id'],
                'category_id' => $product['category_id'],
                'offer_id' => $product['offer_id'],
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $product['price'],
                'cluster1' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster2' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster3' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster4' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster5' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster6' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster7' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster8' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0],
                'cluster9' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0]    // Дон
            ];

            foreach ($product['stocks'] as $stock) {
                if ($stock['cluster'] == "Москва и МО") {
                    $p['cluster1']['free'] += $stock['free'];
                    $p['cluster1']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Центр") {
                    $p['cluster2']['free'] += $stock['free'];
                    $p['cluster2']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Северо-Запад") {
                    $p['cluster3']['free'] += $stock['free'];
                    $p['cluster3']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Поволжье") {
                    $p['cluster4']['free'] += $stock['free'];
                    $p['cluster4']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Юг") {
                    $p['cluster5']['free'] += $stock['free'];
                    $p['cluster5']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Урал") {
                    $p['cluster6']['free'] += $stock['free'];
                    $p['cluster6']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Сибирь") {
                    $p['cluster7']['free'] += $stock['free'];
                    $p['cluster7']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Дальний восток") {
                    $p['cluster8']['free'] += $stock['free'];
                    $p['cluster8']['promised'] += $stock['promised'];
                }
                if ($stock['cluster'] == "Дон") {
                    $p['cluster9']['free'] += $stock['free'];
                    $p['cluster9']['promised'] += $stock['promised'];
                }
            }

            foreach ($product['sell1'] as $sell1) {
                if ($sell1['cluster'] == "Москва и МО") $p['cluster1']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Центр") $p['cluster2']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Северо-Запад") $p['cluster3']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Поволжье") $p['cluster4']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Юг") $p['cluster5']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Урал") $p['cluster6']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Сибирь") $p['cluster7']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Дальний восток") $p['cluster8']['sell1'] += $sell1['qty'];
                if ($sell1['cluster'] == "Дон") $p['cluster9']['sell1'] += $sell1['qty'];
            }
            foreach ($product['sell3'] as $sell3) {
                if ($sell3['cluster'] == "Москва и МО") $p['cluster1']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Центр") $p['cluster2']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Северо-Запад") $p['cluster3']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Поволжье") $p['cluster4']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Юг") $p['cluster5']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Урал") $p['cluster6']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Сибирь") $p['cluster7']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Дальний восток") $p['cluster8']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Дон") $p['cluster9']['sell3'] += $sell3['qty'];
            }

//            if ($p['cluster1'] > 0 or $p['cluster2'] > 0 or $p['cluster3'] > 0 or $p['cluster4'] > 0 or $p['cluster5'] > 0 or $p['cluster6'] > 0 or $p['cluster7'] > 0 or $p['cluster8'] > 0) {
            $result[] = $p;
//            }
        }

        return $result;
    }
}
