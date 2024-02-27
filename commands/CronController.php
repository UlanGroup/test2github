<?php

namespace app\commands;

use app\Ozon\Entity\Order;
use app\Wildberries\DTO\SaleDTO;
use app\Wildberries\DTO\StockDTO;
use app\Wildberries\Factory\SaleFactory;
use app\Wildberries\Service\WbService;
use Yii;

use app\Order\Factory\DashboardFactory;
use app\Order\Service\DashboardService;
use app\Ozon\DTO\FunnelDTO;
use app\Ozon\DTO\OrderDTO;
use app\Ozon\Factory\OrderFactory as OrderFactoryOz;
use app\Ozon\Factory\StockFactory;
use app\Ozon\Service\OrderService;
use app\Ozon\Service\OzonService;
use app\Ozon\Service\ProductService;
use app\Service\OzonApiService;
use app\Telegram\Service\Client;
use yii\console\Controller;

use app\Wildberries\Factory\OrderFactory as OrderFactoryWb;
use app\Wildberries\DTO\OrderDTO as OrderDTOWb;

class CronController extends Controller
{

    // каждый час
    public function actionHour()
    {
        date_default_timezone_set("Europe/Moscow");

        // скачать заказы
        $result = OzonApiService::getOrders();
        if (!empty($result) && !empty($result['result'])) {
            $orderF = new OrderFactoryOz();
            foreach ($result['result'] as $item) {
                $orderDTO = OrderDTO::ozon($item);
                $orderF->create($orderDTO);
            }
        }

        $orderS = new OrderService();
        $orderS->setProductId(); // привязать product_id к заказам
        $orderS->setCluster(); // привязать cluster к заказам
        $orderS->setStatus(); // поменять статус заказам

        // обновить дашборд
        $dashboardS = new DashboardService();
        $dashboardS->reCount();


        // скачать воронку
        $result = OzonApiService::getFunnel();
        if (!empty($result) && !empty($result['result'])) {
            $dashF = new DashboardFactory();
            foreach ($result['result']['data'] as $item) {
                $dto = FunnelDTO::ozon($item);
                $dashF->addFunnel($dto);
            }
        }

        // скачать продажи и заказы ВБ

        $wbO = WbService::getOrders('2024-01-01');
        if (empty($wbO)) return null;
        $orderF = new OrderFactoryWb();
        foreach ($wbO as $item) {
            $orderDTO = OrderDTOWb::wb($item);
            $orderF->create($orderDTO);
        }

        // скачать продажи и заказы ВБ
        $wbS = WbService::getSales('2024-01-01');
        if (empty($wbS)) return null;
        $saleF = new SaleFactory();
        foreach ($wbS as $item) {
            $saleDTO = SaleDTO::wb($item);
            $saleF->create($saleDTO);

        }

        // добавить расходы по заказам в базу
        $orders = Order::find()->where(['status' => [4, 5], 'commission' => NULL])->asArray()->limit(500)->all();
        $orders = array_column($orders, 'posting_number');
        foreach ($orders as $posting_number) {
            $data = OzonApiService::getTransaction($posting_number);
            if (empty($data)) return null;
            $orderS = new OrderService();
            $orderS ->setTransaction($posting_number, $data);
        }


        // скачать остатки
        Yii::$app->db->createCommand()->delete('ozon_stock')->execute(); // очистить таблицу

        $result = OzonApiService::getStock();

        $stockF = new StockFactory();
        foreach ($result['result']['rows'] as $item) {
            $stockF->create($item);
        }

        $productS = new ProductService();
        $productS->setProductId(); // привязать product_id к остаткам
        $productS->setCluster(); // привязать cluster к остаткам
    }


    // в час ночи
    public function actionOne()
    {
        date_default_timezone_set("Europe/Moscow");

        $ozonS = new OzonService();
        $ozonS->createProducts();
        $ozonS->createProducts(true);
    }


    // в два ночи
    public function actionTwo()
    {
        date_default_timezone_set("Europe/Moscow");
    }


    // в 5 мск (10 утра бали)
    public function actionFive()
    {
        date_default_timezone_set("Europe/Moscow");

        Client::dayReport();
    }


    // в 17 мск (22 бали)
    public function actionEvening()
    {
        date_default_timezone_set("Europe/Moscow");
    }


}
