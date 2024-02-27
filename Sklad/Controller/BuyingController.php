<?php

namespace app\Sklad\Controller;

use Yii;

use app\Log\Entity\Error;
use app\Sklad\Entity\BuyingProduct;
use app\Sklad\Entity\Supplier;

use app\Sklad\Repository\BuyingRepository;

use app\Sklad\Factory\BuyingFactory;

use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;

class BuyingController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    // все закупки
    public function actionAll()
    {
        $buyingR = new BuyingRepository();
        $buyings = $buyingR->all(true);

        $suppliers = Supplier::find()->all();

        return ['alert' => ['msg' => 'Обновили', 'type' => 'success'], 'buyings' => $buyings, 'suppliers' => $suppliers];
    }


    public function actionCreate()
    {
        $buyingFactory = new BuyingFactory();
        $buying = $buyingFactory->create();

        $buyingR = new BuyingRepository();
        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка создана', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    public function actionAddProduct()
    {
        $post = Yii::$app->getRequest()->post(); // [product_id]

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one((int)$post['id']);

        $buyingFactory = new BuyingFactory();
        $buyingFactory->addProduct($buying, $post['product_id']);

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка создана', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    public function actionRemoveProduct()
    {
        $post = Yii::$app->getRequest()->post(); // [product_id]

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one((int)$post['id']);

        $buyingFactory = new BuyingFactory();
        $buyingFactory->removeProduct($buying, $post['product_id']);

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка создана', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    // поменять статус и дату на отправлен
    public function actionSended()
    {
        $post = Yii::$app->getRequest()->post(); // [id, sended]

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one($post['id']);

        $buying->setSended($post['sended']);

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка обновлена', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    // удалить
    public function actionDel()
    {
        $post = Yii::$app->getRequest()->post();

        $buyingFactory = new BuyingFactory();
        $buyingFactory->del($post);

        $buyingR = new BuyingRepository();
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка удалена', 'type' => 'success'], 'buyings' => $buyings];
    }


    // Установить поставщика
    public function actionSupplier()
    {
        $post = Yii::$app->getRequest()->post();

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one($post['id']);

        $buying->setSupplier($post['supplier_id']);

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка обновлена', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    // сохранить
    public function actionSave()
    {
        $post = Yii::$app->getRequest()->post();

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one($post['id']);

        $buying->track = $post['track'];
        $buying->cost = (int)$post['cost'];
        $buying->delivery = $post['delivery'];
        $buying->weight = (int)$post['weight'];
        $buying->planned = date('Y-m-d H:i:s', strtotime($post['planned']));
        if (!$buying->save()) Error::error('$buying->create', $buying->getErrors());

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка обновлена', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    // Сменить статус
    public function actionStatus()
    {
        $post = Yii::$app->getRequest()->post();

        $buyingR = new BuyingRepository();
        $buying = $buyingR->one($post['id']);

        if (!empty($post['status']) && $post['status'] == 2) $buying->setPlanned();
        if (!empty($post['status']) && $post['status'] == 3) $buying->setPayed(date('Y-m-d H:i:s'));
        if (!empty($post['status']) && $post['status'] == 4) $buying->setSended(date('Y-m-d H:i:s'));
        if (!empty($post['status']) && $post['status'] == 5) $buying->setArrived(date('Y-m-d H:i:s'));

        $buying = $buyingR->oneArray($buying->id);
        $buyings = $buyingR->all(true);

        return ['alert' => ['msg' => 'Закупка обновлена', 'type' => 'success'], 'buyings' => $buyings, 'buying' => $buying];
    }


    // Изменить цену и кол-во продукта в закупке
    public function actionSaveProduct()
    {
        $post = Yii::$app->getRequest()->post();

        $bp = BuyingProduct::find()->where(['buying_id' => $post['buying_id'], 'product_id' => $post['product_id']])->one();
        if (empty($bp)) return ['alert' => ['msg' => 'Не найдено', 'type' => 'error']];

        $bp->price = $post['price'];
        $bp->qty = $post['qty'];
        if (!$bp->save()) Error::error('$bp->create', $bp->getErrors());

        $buyingR = new BuyingRepository();
        $buying = $buyingR->oneArray($post['buying_id']);

        return ['alert' => ['msg' => 'Товар обновлен', 'type' => 'success'], 'buying' => $buying];
    }


}
