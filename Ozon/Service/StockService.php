<?php

namespace app\Ozon\Service;

use app\Ozon\Entity\Order;
use app\Ozon\Entity\Product;

use app\Ozon\Repository\RegionRepository;

// Сервис для изменения заказов
class StockService
{

    // рассчитать кол-во необходимой поставки в каждый кластер
    public function clusterShipping()
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
                'cluster8' => ['free' => 0, 'promised' => 0, 'sell1' => 0, 'sell3' => 0]
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
                    $p['cluster7']['free'] += $stock['free'];
                    $p['cluster7']['promised'] += $stock['promised'];
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
                if ($sell1['cluster'] == "Дальний восток") $p['cluster7']['sell1'] += $sell1['qty'];
            }
            foreach ($product['sell3'] as $sell3) {
                if ($sell3['cluster'] == "Москва и МО") $p['cluster1']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Центр") $p['cluster2']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Северо-Запад") $p['cluster3']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Поволжье") $p['cluster4']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Юг") $p['cluster5']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Урал") $p['cluster6']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Сибирь") $p['cluster7']['sell3'] += $sell3['qty'];
                if ($sell3['cluster'] == "Дальний восток") $p['cluster7']['sell3'] += $sell3['qty'];
            }

            $result[] = $p;
        }

//        return $result;


        $shipping = [];
        foreach ($result as $p) {
            $row['product_id'] = $p['id'];
            $row['offer_id'] = $p['offer_id'];
            $row['name'] = $p['name'];
            $row['Москва и МО'] = 0;
            $row['Центр'] = 0;
            $row['Северо-Запад'] = 0;
            $row['Поволжье'] = 0;
            $row['Юг'] = 0;
            $row['Урал'] = 0;
            $row['Сибирь'] = 0;
            $row['Дальний восток'] = 0;

            if (($p['cluster1']['free'] + $p['cluster1']['promised']) <= ($p['cluster1']['sell1'] / 2)) {
                $row['Москва и МО'] = $p['cluster1']['sell3'] - ($p['cluster1']['free'] + $p['cluster1']['promised']);
            }

            if (($p['cluster2']['free'] + $p['cluster2']['promised']) <= ($p['cluster2']['sell1'] / 2)) {
                $row['Центр'] = $p['cluster2']['sell3'] - ($p['cluster2']['free'] + $p['cluster2']['promised']);
            }

            if (($p['cluster3']['free'] + $p['cluster3']['promised']) <= ($p['cluster3']['sell1'] / 2)) {
                $row['Северо-Запад'] = $p['cluster3']['sell3'] - ($p['cluster3']['free'] + $p['cluster3']['promised']);
            }

            if (($p['cluster4']['free'] + $p['cluster4']['promised']) <= ($p['cluster4']['sell1'] / 2)) {
                $row['Поволжье'] = $p['cluster4']['sell3'] - ($p['cluster4']['free'] + $p['cluster4']['promised']);
            }

            if (($p['cluster5']['free'] + $p['cluster5']['promised']) <= ($p['cluster5']['sell1'] / 2)) {
                $row['Юг'] = $p['cluster5']['sell3'] - ($p['cluster5']['free'] + $p['cluster5']['promised']);
            }

            if (($p['cluster6']['free'] + $p['cluster6']['promised']) <= ($p['cluster6']['sell1'] / 2)) {
                $row['Урал'] = $p['cluster6']['sell3'] - ($p['cluster6']['free'] + $p['cluster6']['promised']);
            }

            if (($p['cluster7']['free'] + $p['cluster7']['promised']) <= ($p['cluster7']['sell1'] / 2)) {
                $row['Сибирь'] = $p['cluster7']['sell3'] - ($p['cluster7']['free'] + $p['cluster7']['promised']);
            }

            if (($p['cluster8']['free'] + $p['cluster8']['promised']) <= ($p['cluster8']['sell1'] / 2)) {
                $row['Дальний восток'] = $p['cluster8']['sell3'] - ($p['cluster8']['free'] + $p['cluster8']['promised']);
            }

            if ($row['Москва и МО'] > 0 or $row['Центр'] > 0 or $row['Северо-Запад'] > 0 or $row['Поволжье'] > 0 or $row['Юг'] > 0 or $row['Урал'] > 0 or $row['Сибирь'] > 0 or $row['Дальний восток'] > 0) {
                $shipping[] = $row;
            }
            unset($row);
        }

        return $shipping;
    }

}