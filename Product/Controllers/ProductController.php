<?php

namespace app\Product\Controllers;

use app\Ozon\Repository\ProductRepository;

use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;

class ProductController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    // товары
    public function actionAll()
    {
        $productR = new ProductRepository();
        $products = $productR->all(true);

        return ['alert' => ['msg' => 'Товары', 'type' => 'success'], 'products' => $products];
    }

}
