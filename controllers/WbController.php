<?php

namespace app\controllers;

use app\Wildberries\DTO\OrderDTO;
use app\Wildberries\DTO\ProductDTO;
use app\Wildberries\DTO\StockDTO;
use app\Wildberries\Entity\Sale;
use app\Wildberries\Factory\OrderFactory;
use app\Wildberries\Factory\ProductFactory;
use app\Wildberries\Factory\SaleFactory;

use app\Wildberries\Factory\StockFactory;
use app\Wildberries\Repository\OrderRepository;
use app\Wildberries\Repository\SaleRepository;
use app\Wildberries\Service\WbService;

use app\Wildberries\DTO\SaleDTO;
use Yii;

use yii\web\Controller;

class WbController extends Controller
{

    // получить продажи от Wb
    public function actionSaleWb()
    {
        $wbS = WbService::getSales('2024-01-01');
        if (empty($wbS)) return null;

        $saleF = new SaleFactory();
        foreach ($wbS as $item) {
            $saleDTO = SaleDTO::wb($item);
            $saleF->create($saleDTO);

        }

        return 'sale-wb-ok';
    }

    public function actionOrderWb()
    {
        $wbO = WbService::getOrders('2024-01-01');
        if (empty($wbO)) return null;


        $orderF = new OrderFactory();
        foreach ($wbO as $item) {
            $orderDTO = OrderDTO::wb($item);
            $orderF->create($orderDTO);

        }
        return 'order-wb-ok';
    }


    // получить остатки от ВБ
    public function actionStockWb()
    {
        $wbSt = WbService::getStock('2020-02-10');
        if (empty($wbSt)) return null;

        $stockF = new StockFactory();
        foreach ($wbSt as $item) {
            $stockDTO = StockDTO::wb($item);
            $stockF->create($stockDTO);
        }
        return 'stock-wb-ok';
    }

    // получить товары от Wb
    public function actionProductWb()
    {
        $wbPr = WbService::getProduct();
        if (empty($wbPr)) return null;

        $productF = new ProductFactory();
        foreach ($wbPr['cards'] as $item) {
            $productDTO = ProductDTO::wb($item);
            $productF->create($productDTO);

        }

        return 'product-wb-ok';
    }

    public function actionTest()
    {
        $wb_sales = Sale::find()->select(['DATE(date) as created_at', 'SUM(forPay) as revenue'])->where(['orderType' => 'Клиентский'])->groupBy('DATE(date)')->asArray()->all();

        return $wb_sales;
    }

    public function actionSale()
    {
        $q = new SaleRepository();
        $wb_q = $q->all();

        return $wb_q;
    }

    public function actionOrder()
    {
        $q = new OrderRepository();
        $wb_q = $q->all();

        return $wb_q;
    }
}
