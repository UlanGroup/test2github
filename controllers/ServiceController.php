<?php

namespace app\controllers;

use app\Finance\Entity\Expense;
use app\Finance\Factory\ExpenseFactory;
use app\Log\Entity\Error;
use app\Ozon\Repository\OrderRepository;
use app\Sklad\Entity\BuyingProduct;
use app\Sklad\Entity\Supplier;
use app\Wildberries\Service\WbService;
use Yii;

use app\Order\Factory\DashboardFactory;
use app\Ozon\DTO\FunnelDTO;
use app\Ozon\DTO\MoySkladDTO;
use app\Ozon\DTO\OrderDTO;
use app\Ozon\DTO\ProductDTO;
use app\Ozon\Entity\Order;
use app\Ozon\Entity\Product;

use app\Ozon\Factory\MoySkladFactory;
use app\Ozon\Factory\OrderFactory;

use app\Order\Service\DashboardService;
use app\Ozon\Factory\ProductFactory;
use app\Ozon\Factory\StockFactory;
use app\Ozon\Service\OrderService;
use app\Ozon\Service\OzonService;
use app\Ozon\Service\ProductService;
use app\Ozon\Service\StockService;
use app\Service\MoySkladApiService;
use app\Service\OzonApiService;

use app\Sklad\Entity\Buying;
use yii\web\Controller;

class ServiceController extends Controller
{

    // получить товары от OZON
    public function actionProductsOzon()
    {
        $ozonS = new OzonService();
        $ozonS->createProducts(true);
    }


    // обновить товары OZON в базе, у которых пустая часть полей
    public function actionProductOzon()
    {
        // существующие товары
        $products = Product::find()->where(['or', ['name' => null], ['sku' => null], ['category_id' => null]])->all();

        $ozonS = new OzonService();
        foreach ($products as $product) {
            $ozonS->updateProduct($product);
        }


        // из заказов
        $pF = new ProductFactory();

        $orders = Order::find()->where(['product_id' => null])->groupBy('sku')->all();

        foreach ($orders as $order) {
            $result = OzonApiService::getProductBySku($order->sku);
            if (empty($result) or empty($result['result'])) continue;

            $product = $pF->create($result['result']['id'], $result['result']['offer_id'], 0);

            $dto = ProductDTO::ozon($result['result']);
            $product->upd($dto);
        }
    }


    // получить заказы от OZON
    public function actionOrdersOzon()
    {
        $result = OzonApiService::getOrders();
        if (empty($result)) return null;

        return $result;
        $orderF = new OrderFactory();
        foreach ($result['result'] as $item) {
            $orderDTO = OrderDTO::ozon($item);
            $orderF->create($orderDTO);
        }

        $orderS = new OrderService();
        $orderS->setProductId(); // привязать product_id к заказам
        $orderS->setCluster(); // привязать cluster к заказам
        $orderS->setStatus(); // поменять статус заказам
    }

    // получить транзакции от OZON
    public function actionTransaction()
    {
        $orders = Order::find()->where(['status' => [4, 5], 'commission' => NULL])->asArray()->limit(500)->all();
        $orders = array_column($orders, 'posting_number');

        foreach ($orders as $posting_number) {
            $data = OzonApiService::getTransaction($posting_number);
            if (empty($data)) return null;
            $orderS = new OrderService();
            $orderS ->setTransaction($posting_number, $data);
        }
        return "100-ok"; // выполнять эту функцию когда обновляется статус на 4-5
    }


    // получить воронку от OZON
    public function actionFunnelOzon()
    {
        $result = OzonApiService::getFunnel();

        $dashF = new DashboardFactory();
        foreach ($result['result']['data'] as $item) {
            $dto = FunnelDTO::ozon($item);
            $dashF->addFunnel($dto);
        }
        return $result;
    }


    // получить остатки от OZON
    public function actionStockOzon()
    {
        Yii::$app->db->createCommand()->delete('ozon_stock')->execute();

        $result = OzonApiService::getStock();

        $stockF = new StockFactory();
        foreach ($result['result']['rows'] as $item) {
            $stockF->create($item);
        }

        $productS = new ProductService();
        $productS->setProductId(); // привязать product_id к остаткам
        $productS->setCluster(); // привязать cluster к остаткам

        return $result;
    }


    public function actionSaleWb()
    {
        $wbS = new WbService();
        return $wbS->getSales('2024-01-01');
    }


    public function actionOrderWb()
    {
        $wbS = new WbService();
        return $wbS->getOrders('2024-01-01');
    }


    // пересчитать Dashboard вручную
    public function actionDashboard()
    {
        $dashboardS = new DashboardService();
        return $dashboardS->reCount();
    }


    //  рассчитать кол-во необходимой поставки в каждый кластер
    public function actionShipping()
    {
        $stockS = new StockService();
        $result = $stockS->clusterShipping();

        return $result;
    }


    //  получить список товаров из сервиса МойСклад
    public function actionMoySklad()
    {
        $result = MoySkladApiService::getAssort();

        $assortF = new MoySkladFactory();
        foreach ($result['rows'] as $item) {
            $dto = MoySkladDTO::assortment($item);
//            return $dto;
            $assortF->create($dto);
        }

        return "actionMoySklad - Ok";
    }





    // НЕ НУЖНО


    // привязать product_id к заказу
    public function actionS()
    {

        $q = Buying::find()->select(['supplier.name as name', 'buying.user_id', 'buying.created', 'buying.payed', 'buying.planned', 'buying.sended', 'buying.arrived', 'buying.track', 'buying.cost', 'buying.weight', 'buying.delivery', 'buying.duration', 'buying.status', 'buying.del'])
            ->join('LEFT JOIN', 'supplier', 'supplier.id = buying.supplier_id');

        $q->asArray();

        return $q->orderBy('buying.created DESC')->all();
    }


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


    public function actionTest()
    {
        mkdir('./users', 0777, true);
        mkdir('./users/mini', 0777, true);
    }


    public function actionCsv()
    {
        $file = file('./file1.txt');

        foreach ($file as $line) {

            $data = explode(';', $line);

            $fields = [
                '0 Номер Заказа',
                '1 Дата Заказа',
                '2 Артикул',
                '3 Наименование товара',
                '4 Количество',
                '5 Ед.измерения',
                '6 Цена',
                '7 Контрагент',
                '8 Плановая дата получения'
            ];

            $created = $planned = null;
            if (!empty($data[1])) $created = date('Y-m-d', strtotime($data[1]));
            if (!empty($data[8])) $planned = date('Y-m-d', strtotime($data[8]));

            $product = Product::find()->where(['offer_id' => trim($data[2])])->one();
            if (empty($product)) continue;

            $supplier = Supplier::find()->where(['name' => trim($data[7])])->one();
            $supplier_id = null;
            if (!empty($supplier->id)) $supplier_id = $supplier->id;


            $buying = Buying::findOne($data[0]);
            if (empty($buying)) {
                $buying = new Buying();
                $buying->id = (int)$data[0];
                $buying->supplier_id = $supplier_id;
                $buying->created = $created;
                $buying->planned = $planned;
                $buying->payed = $created;
                $buying->sended = $created;
                $buying->arrived = $planned;
                $buying->user_id = 1;
                $buying->status = 5;
                if (!$buying->save()) Error::error('$buying->create', $buying->getErrors());
            }

            $bp = BuyingProduct::find()->where(['buying_id' => $buying->id, 'product_id' => $product->id, 'qty' => (int)$data[4]])->one();
            if (empty($bp)) {
                $bp = new BuyingProduct();
                $bp->buying_id = $buying->id;
                $bp->product_id = $product->id;
                $bp->price = $data[6];
                $bp->qty = (int)$data[4];
                $bp->accept_qty = (int)$data[4];
                $bp->status = 1;
                if (!$bp->save()) Error::error('$bp->create', $bp->getErrors());

                $buying->reCost();
            }
        }
    }


    public function actionStock()
    {
        $file = file('./stock.txt');

        foreach ($file as $line) {
            $data = explode(';', $line);

            $product = Product::find()->where(['offer_id' => trim($data[0])])->one();
            if (empty($product)) {
                Error::error('нет товара', $data[0]);
                continue;
            }

            $buying = Buying::findOne(200);

            $bp = BuyingProduct::find()->where(['buying_id' => $buying->id, 'product_id' => $product->id])->one();
            if (empty($bp)) {
                $bp = new BuyingProduct();
                $bp->buying_id = $buying->id;
                $bp->product_id = $product->id;
                $bp->price = (float)$data[2];
                $bp->qty = (int)$data[1];
                $bp->accept_qty = (int)$data[1];
                $bp->status = 1;
                if (!$bp->save()) Error::error('$bp->create', $bp->getErrors());
                $buying->reCost();
            }
        }
    }


    public function actionB()
    {
        $items = BuyingProduct::find()->select(['ozon_product.id', 'ozon_product.offer_id', 'buying_product.qty', 'buying_product.price'])
            ->where(['buying_id' => 200])
            ->join('LEFT JOIN', 'ozon_product', 'ozon_product.id = buying_product.product_id')
            ->asArray()->all();

        $items = array_column($items, 'offer_id');

        return $items;
    }


    public function actionCron()
    {
        // скачать заказы
        $result = OzonApiService::getOrders();
        if (!empty($result) && !empty($result['result'])) {
            $orderF = new OrderFactory();
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

    public function actionExp()
    {
        $data = [
            307 => 14800,
            306 => 16320,
            305 => 3650,
            304 => 17880,
            300 => 13000,
            299 => 14000,
            297 => 12500,
            294 => 18000,
            291 => 14800,
            287 => 8250,
            286 => 230000,
            285 => 51240,
            284 => 8687,
            276 => 23650,
            275 => 96800,
            274 => 236500,
            273 => 9600,
            271 => 140000,
            270 => 237800,
            269 => 190000,
            268 => 305500,
            264 => 11000,
            263 => 36180,
            262 => 10970,
            261 => 3850,
            260 => 6090,
            259 => 11500,
            258 => 7360,
            257 => 8265,
            256 => 55400,
            255 => 14649.28,
            254 => 12500,
            253 => 410000,
            252 => 11563.61,
            251 => 9000,
            250 => 12180,
            249 => 9500,
            248 => 353000,
            247 => 281000,
            246 => 9472,
            245 => 25616,
            244 => 102029,
            242 => 269774,
            241 => 102719,
            240 => 14840,
            239 => 13205,
            238 => 52808,
            237 => 11642,
            236 => 361880,
            235 => 298400,
            234 => 11260,
            233 => 9488,
            232 => 67633,
            231 => 8448,
            230 => 224400,
            229 => 246184.2,
            228 => 9453,
            227 => 6933,
            226 => 18569,
            225 => 366883,
            224 => 175980,
            223 => 63277,
            222 => 19220,
            221 => 200,
            220 => 55872,
            219 => 400,
            218 => 7943,
            217 => 137709.52,
            216 => 43036,
            215 => 11981,
            214 => 4500,
            213 => 21094,
            212 => 3088.57,
            211 => 26708,
            210 => 35367.94,
            209 => 35310.28,
            208 => 4298.56,
            207 => 22094.26,
            206 => 6044,
            205 => 33579.89,
            204 => 100,
            203 => 3600,
            202 => 39400,
            201 => 11137.76,
            200 => 21671,
            199 => 29800.54,
            198 => 200,
            196 => 3300,
            195 => 24000,
            194 => 100,
            193 => 45973,
            192 => 16820,
            191 => 13919,
            190 => 22100,
            189 => 76646,
            188 => 200,
            187 => 5850,
            186 => 1000,
            185 => 3000,
            184 => 15300,
            183 => 31684,
            182 => 1000,
            181 => 14000,
            180 => 6262,
            179 => 300,
            178 => 4578,
            177 => 73535,
            176 => 24470,
            175 => 41500,
            174 => 18200,
            173 => 13816,
            172 => 5620,
            171 => 100,
            170 => 100,
            169 => 100,
            168 => 59180,
            167 => 581564.7,
            166 => 18200,
            165 => 17894.6,
            164 => 13307,
            163 => 42945,
            161 => 3374,
            160 => 1000,
            159 => 51224,
            157 => 21217,
            156 => 6088,
            154 => 392480,
            153 => 311150,
            152 => 1030841,
            151 => 1800.08,
            150 => 6427,
            149 => 21505,
            148 => 5865,
            147 => 11194,
            146 => 4800,
            145 => 465159,
            143 => 3256,
            142 => 889671,
            141 => 310466,
            140 => 185480,
            139 => 9700,
            136 => 20971,
            135 => 4038,
            134 => 3691,
            133 => 21268.94,
            132 => 11000,
            131 => 126949,
            130 => 11212,
            129 => 6100,
            128 => 632509,
            126 => 3082,
            125 => 528412,
            124 => 236042,
            123 => 8252,
            122 => 277372,
            120 => 13506,
            119 => 9900,
            118 => 4071,
            117 => 387016,
            116 => 169786,
            115 => 7406,
            114 => 5541,
            113 => 69758,
            112 => 3030,
            110 => 2778,
            109 => 100081,
            108 => 65280,
            106 => 10160,
            105 => 3050,
            103 => 2449,
            102 => 248778,
            101 => 16813,
            100 => 106309,
            99 => 3835,
            98 => 276536,
            96 => 130094,
            94 => 32824,
            91 => 194815,
            90 => 3352,
            89 => 29862,
            88 => 118145.6,
            85 => 2950,
            84 => 23875,
            82 => 26276,
            81 => 249071,
            80 => 155007.85,
            79 => 4900,
            78 => 28489.6,
            77 => 264963,
            76 => 7600,
            75 => 86381,
            74 => 4656,
            72 => 3352,
            71 => 61352,
            70 => 17235,
            69 => 23008,
            68 => 8330,
            67 => 317528.15,
            66 => 106876,
            65 => 4921,
            63 => 1687.53,
            62 => 168696,
            61 => 4560,
            60 => 32400,
            59 => 17235,
            58 => 392,
            57 => 18663,
            56 => 2916,
            55 => 2818,
            54 => 3293.2,
            53 => 13600,
            52 => 81156,
            51 => 30482,
            50 => 27199,
            49 => 584561,
            48 => 151456,
            47 => 449068,
            46 => 5206,
            45 => 3543,
            44 => 7936,
            43 => 10832.63,
            42 => 3352,
            41 => 14836.81,
            40 => 5117,
            39 => 2773,
            38 => 13545,
            37 => 2514,
            36 => 4940,
            35 => 5000,
            34 => 3226,
            33 => 4275,
            32 => 28357,
            31 => 19600,
            30 => 178248,
            29 => 15150,
            28 => 3340,
            27 => 152598,
            26 => 230854,
            25 => 31904,
            24 => 122674,
            23 => 128780,
            22 => 39349,
            21 => 74725,
            20 => 3260,
            19 => 16530,
            18 => 13900,
            17 => 11775,
            16 => 5600,
            15 => 1250,
            14 => 12170,
            13 => 110164,
            12 => 8100,
            11 => 4405,
            10 => 2600,
            9 => 10105,
            8 => 85598,
            7 => 3100,
            6 => 61338,
            5 => 164754,
            4 => 8900,
            3 => 87879,
            2 => 80505,
            1 => 8800];

        foreach ($data as $key => $val) {
            $buying = Buying::findOne($key);
            if (empty($buying) or $val == 0) continue;
            $buying->delivery = $val;
            $buying->save();

            $ef = new ExpenseFactory();
            $ef->create($buying->id, 0, 2, 8, 0, $val, $buying->created);
        }
    }


    public function actionTest1()
    {
        $orders = Order::find()->select(['DATE(created_at) as date'])->where(['status' => [1, 2, 3]])->groupBy('DATE(created_at)')->orderBy('created_at')->asArray()->all();
        $dates = array_column($orders, 'date');
        return $dates;
    }


    public function actionTest2()
    {
        $orders = Order::find()->select([
            'MONTH(ozon_orders.created_at) as month',
            'YEAR(ozon_orders.created_at) as year',
            'category_ozon.name',
            'SUM(ozon_orders.price) as revenue',
            'SUM(ozon_orders.commission) as commission',
            'SUM(ozon_orders.delivery_cost) as delivery_cost',
            'SUM(ozon_orders.refund_cost) as refund_cost'])
            ->where(['ozon_orders.status' => 4])
            ->joinWith(['product', 'product.category'])
            ->groupBy(['MONTH(ozon_orders.created_at)',
                'YEAR(ozon_orders.created_at)',
                'ozon_product.category_id'])
            ->orderBy('created_at')->asArray()->all();
        return $orders;
    }


}
