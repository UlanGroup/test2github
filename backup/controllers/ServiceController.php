<?php

namespace app\controllers;

use app\Order\DTO\OrderDTO;
use app\Order\DTO\ProductDTO;

use app\Order\Factory\OrderFactory;
use app\Order\Factory\ProductFactory;
use app\Order\Factory\ProductStockFactory;

use app\Order\Service\OrderService;
use app\Service\CurlService;

use yii\web\Controller;

class ServiceController extends Controller
{

    // получить товары от OZON
    public function actionOzonProducts()
    {
        $result = CurlService::getProducts();
        if (empty($result)) return null;

        $productF = new ProductFactory();

        foreach ($result['result']['items'] as $item) {
            $dto = ProductDTO::ozon($item);
            $productF->create($dto);
        }
    }


    // получить заказы от OZON
    public function actionGetOzonOrders()
    {
        $result = CurlService::getOrders();
        if (empty($result)) return null;

        $orderF = new OrderFactory();

        foreach ($result['result'] as $item) {
            $dto = OrderDTO::ozon($item);
            $orderF->create($dto);
        }

        $orderS = new OrderService();
        $orderS->setProductId(); // привязать product_id к заказам
        $orderS->setCluster(); // привязать cluster к заказам

        return 'ok';
    }


    // получить остатки от OZON
    public function actionOzonStock()
    {
        $result = CurlService::getStock();

        $pwF = new ProductStockFactory();

        foreach ($result['result']['rows'] as $item) {
            $pwF->create($item);
        }
    }


    // TEST

    public function actionS()
    {
//        return Product::find()->joinWith(['stocks'])->orderBy('product.id DESC')->asArray()->all();
    }




    // НЕ НУЖНО

    // привязать product_id к заказу
    public function actionP()
    {
        $orderS = new OrderService();
        $orderS->setProductId();
    }


    // привязать кластер к заказу
    public function actionC()
    {
        $orderS = new OrderService();
        $orderS->setCluster();
    }


}
