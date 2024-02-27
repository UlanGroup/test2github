<?php

namespace app\controllers;

use app\Order\Repository\OrderRepository;

use agielks\yii2\jwt\JwtBearerAuth;
use app\Order\Repository\ProductRepository;
use Yii;
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


    // Стор
    public function actionStore()
    {
        $get = Yii::$app->getRequest()->get();

        $productR = new ProductRepository();
        $store = $productR->storage($get['period']);
        $products = $productR->all(true);

        return ['products' => $products, 'store' => $store, 'alert' => ['msg' => 'Лови Стор', 'type' => 'success']];
    }


    // товары
    public function actionProducts()
    {
        $productR = new ProductRepository();
        $products = $productR->all(true);

        return ['products' => $products, 'alert' => ['msg' => 'Лови товары', 'type' => 'success']];
    }


    // заказы
    public function actionOrders()
    {
        $orderR = new OrderRepository();
        $orders = $orderR->all(true);

        return ['orders' => $orders, 'alert' => ['msg' => 'Саша, лови', 'type' => 'success']];
    }

}
