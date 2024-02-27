<?php

namespace app\controllers;

use app\Order\Factory\ClusterFactory;
use app\Order\Repository\RegionRepository;

use Yii;
use yii\filters\Cors;
use yii\web\Controller;

class ClusterController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
//        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    public function actionCreate()
    {
        $post = Yii::$app->getRequest()->post();

        $clusterFactory = new ClusterFactory();
        $cluster = $clusterFactory->create($post);

        $clusterR = new RegionRepository();
        $clusters = $clusterR->all(true);

        return ['clusters' => $clusters, 'cluster' => $cluster, 'alert' => ['msg' => 'Класстеры создан', 'type' => 'success']];
    }


    public function actionUpdate()
    {
        $post = Yii::$app->getRequest()->post();

        $clusterFactory = new ClusterFactory();
        $clusterFactory->update($post);

        $clusterR = new RegionRepository();
        $clusters = $clusterR->all(true);

        return ['clusters' => $clusters, 'alert' => ['msg' => 'Класстеры обновлены', 'type' => 'success']];
    }

    public function actionDel()
    {
        $post = Yii::$app->getRequest()->post();

        $clusterFactory = new ClusterFactory();
        $clusterFactory->del($post);

        $clusterR = new RegionRepository();
        $clusters = $clusterR->all(true);

        return ['clusters' => $clusters, 'alert' => ['msg' => 'Класстер удален', 'type' => 'success']];
    }
}