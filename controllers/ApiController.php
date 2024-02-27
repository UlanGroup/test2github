<?php

namespace app\controllers;

use app\Ozon\Entity\Order;
use Yii;

use app\Ozon\Entity\Category;

use app\Order\Repository\DashboardRepository;
use app\Ozon\Repository\OrderRepository;
use app\Ozon\Repository\ProductRepository;
use app\Ozon\Repository\RegionRepository;

use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;

class ApiController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
//        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    // Дашборд
    public function actionDashboard()
    {
        $t0 = microtime(true) * 1000;

        $dashR = new DashboardRepository();
        $dashboard = $dashR->all();

        $productR = new ProductRepository();
        $stocks = $productR->cluster();

        $orderOzonR = new OrderRepository();
        $ordersOz = $orderOzonR->all(true, 100);

//        $orderWbR = new OrderWbRepository();
//        $ordersWb = $orderWbR->all(true, 100);
//
//        $orders = array_merge($ordersOz, $ordersWb);

        $orders = $ordersOz;

        $categories = Category::find()->asArray()->orderBy('name')->all();

        $t1 = microtime(true) * 1000;
        return ['dashboard' => $dashboard, 'categories' => $categories, 'stocks' => $stocks, 'orders' => $orders, 'alert' => ['msg' => 'Обновили за ' . round($t1 - $t0) . ' ms', 'type' => 'success']];
    }


//    // Стор
//    public function actionStore()
//    {
//        $get = Yii::$app->getRequest()->get();
//
//        $productR = new ProductRepository();
//        $store = $productR->storage($get['period']);
//        $products = $productR->all(true);
//
//        return ['products' => $products, 'store' => $store, 'alert' => ['msg' => 'Лови Стор', 'type' => 'success']];
//    }


    // товары
    public function actionProducts()
    {
        $productR = new ProductRepository();
        $products = $productR->all(true);

        return ['products' => $products, 'alert' => ['msg' => 'Лови товары', 'type' => 'success']];
    }


    // кластеры
    public function actionClusters()
    {
        $clusterR = new RegionRepository();
        $clusters = $clusterR->all(true);

        return ['clusters' => $clusters, 'alert' => ['msg' => 'Лови товары', 'type' => 'success']];
    }


    // заказы
    public function actionOrders()
    {
        $orderR = new OrderRepository();
        $orders = $orderR->all(true);

        return ['orders' => $orders, 'alert' => ['msg' => 'Заказы', 'type' => 'success']];
    }

    public function actionPnl()
    {
        // Получаем данные из запроса
        $data = Order::find()->select([
            'ozon_orders.created_at as created_at',
            'MONTH(ozon_orders.created_at) as month',
            'YEAR(ozon_orders.created_at) as year',
            'category_ozon.name',
            'category_ozon.id as category_id',
            'SUM(ozon_orders.price) as revenue',
            'SUM(ozon_orders.commission) as commission',
            'SUM(ozon_orders.delivery_cost) as delivery_cost',
            'SUM(ozon_orders.refund_cost) as refund_cost'])
            ->where(['ozon_orders.status' => 4])
            ->joinWith(['product', 'product.category'])
            ->groupBy(['MONTH(ozon_orders.created_at)', 'YEAR(ozon_orders.created_at)', 'ozon_product.category_id'])
            ->orderBy('ozon_orders.created_at')
            ->asArray()->all();

        // Если нет продаж, то просто выходим
        if (!$data) return [];

        // Заполняем массив данными из запроса
        $pnl = [];
        foreach ($data as $row) {
            if (!isset($pnl[$row['category_id']])) $pnl[$row['category_id']] = ['category_id' => $row['category_id'], 'name' => $row['name'], 'revenue' => 0, 'dates' => []];

            $dateKey = $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '-01';

            $pnl[$row['category_id']]['revenue'] += $row['revenue'];

            $pnl[$row['category_id']]['dates'][$dateKey] = [
                'revenue' => $row['revenue'],
                'commission' => $row['commission'],
                'delivery_cost' => $row['delivery_cost'],
                'refund_cost' => $row['refund_cost']
            ];
        }

        usort($pnl, fn($a, $b) => $b['revenue'] <=> $a['revenue']);

        $i = 1;
        foreach ($pnl as &$item) $item['id'] = $i++;

        // Возвращаем результирующий массив
        return ['pnl' => $pnl, 'alert' => ['msg' => 'Pnl', 'type' => 'success']];
    }

}
