<?php

namespace app\Order\Service;

use app\Ozon\Entity\Order;
use app\Wildberries\Entity\Sale;
use app\Ozon\Entity\Product;
use app\Order\Factory\DashboardFactory;

// Сервис для записи в дашборд
class DashboardService
{

    public function reCount()
    {
        $srt = '2022-01-01';
        $end = date("Y-m-d");

        // заказы
        $ozon_orders = Order::find()->select(['DATE(created_at) as created_at', 'SUM(price) as revenue', 'SUM(qty) as solditems'])->where(['status' => 4])->groupBy('DATE(created_at)')->asArray()->all();

        // продажи ВБ
        $wb_sales = Sale::find()->select(['DATE(date) as created_at', 'SUM(forPay) as revenue'])->where(['orderType' => 'Клиентский'])->groupBy('DATE(date)')->asArray()->all();

        // возвраты
        $returns = Order::find()->select(['DATE(created_at) as created_at', 'SUM(qty) as returns'])->where(['status' => 5])->groupBy('DATE(created_at)')->asArray()->all();

        // категории товаров проданные по дням
        $categories = Order::find()
            ->select(['DATE(ozon_orders.created_at) as created_at', 'SUM(ozon_orders.price) as revenue', 'SUM(ozon_orders.qty) as solditems', 'category_ozon.id as category_id', 'category_ozon.name as category_name',
                'SUM(ozon_orders.commission) as commission', 'SUM(ozon_orders.delivery_cost) as delivery_cost', 'SUM(ozon_orders.refund_cost) as refund_cost'])
            ->where(['not', ['ozon_orders.status' => 5]])
            ->joinWith(['product', 'product.category'])
            ->groupBy(['DATE(ozon_orders.created_at)', 'ozon_product.category_id'])
            ->asArray()->all();

        // товары
        // $products = Product::find()->all();

        $dashboard = [];
        while ($srt <= $end) {
            $row = ['date' => $srt, 'revenue' => 0, 'average_check' => 0, 'solditems' => 0];

            // продажи ОЗОН
            foreach ($ozon_orders as $order) {
                if (date('Y-m-d', strtotime($order['created_at'])) == $srt) {
                    $row['revenue'] += (int)$order['revenue'];
                    if (!empty($order['solditems']))$row['solditems'] += (int)$order['solditems'];
                }
            }

            unset($order);

            // продажи ВБ
            foreach ($wb_sales as $order) {
                if (date('Y-m-d', strtotime($order['created_at'])) == $srt) {
                    $row['revenue'] += (int)$order['revenue'];
                    $row['solditems'] += 1;
                }
            }

            $row['average_check'] = round($row['revenue'] / $row['solditems']);

            // возвраты
            foreach ($returns as $return) {
                if ($return['created_at'] == $srt) {
                    $row['returns'] = (int)$return['returns'];
                }
            }

            // продажи категорий товаров
            $salesbycategory = [];
            foreach ($categories as $cat) {
                if (date('Y-m-d', strtotime($cat['created_at'])) == $srt) {
                    $salesbycategory[] = [
                        'c' => (int)$cat['category_id'],
                        'r' => (int)$cat['revenue'],
                        's' => (int)$cat['solditems'],
                        'com' => (int)$cat['commission'],
                        'dev' => (int)$cat['delivery_cost'],
                        'ref' => (int)$cat['refund_cost']];
                }
            }
            $row['salesbycategory'] = json_encode($salesbycategory);

            $dashboard[] = $row;

            $srt = date('Y-m-d', strtotime("$srt +1 day"));
        }


        $dF = new DashboardFactory();
        foreach ($dashboard as $date) {
            $dF->create($date);
        }

        return $dashboard;
    }

}