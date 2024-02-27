<?php

namespace app\Sklad\Factory;

use Yii;

use app\Log\Entity\Error;
use app\Sklad\Entity\Buying;
use app\Sklad\Entity\BuyingProduct;

class BuyingFactory
{

    // создать ордер
    public function create(): ?Buying
    {
        $buying = new Buying();
        $buying->user_id = Yii::$app->user->id;
        if (!$buying->save()) Error::error('$buying->create', $buying->getErrors());

        return $buying;
    }


    // добавить товар в закупку
    public function addProduct(Buying $buying, int $product_id): ?BuyingProduct
    {
        /** @var BuyingProduct $bp */
        $bp = BuyingProduct::find()->where(['buying_id' => $buying->id, 'product_id' => $product_id])->one();
        if (!empty($bp)) return $bp;

        $bp = new BuyingProduct();
        $bp->buying_id = $buying->id;
        $bp->product_id = $product_id;
        $bp->status = 1;
        if (!$bp->save()) Error::error('$bp->create', $bp->getErrors());

        return $bp;
    }


    public function removeProduct(Buying $buying, int $product_id): void
    {
        $bp = BuyingProduct::find()->where(['buying_id' => $buying->id, 'product_id' => $product_id])->one();
        if (!empty($bp)) $bp->delete();
    }


    // отредактировать кластер в библиотеке
    public function del(array $array): ?Buying
    {
        /** @var Buying $buying */
        $buying = Buying::find()->where(['id' => $array['id']])->one();

        $buying->del = 1;
        $buying->save();

        return $buying;
    }


}
