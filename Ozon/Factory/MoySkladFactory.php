<?php

namespace app\Ozon\Factory;

use app\Log\Entity\Error;
use app\Ozon\Entity\Assortment;
use app\Ozon\DTO\MoySkladDTO;
use app\Ozon\Entity\Order;

class MoySkladFactory
{

    public function create(MoySkladDTO $dto): ?Assortment
    {
        /** @var Assortment $assortment */
        $assortment = Assortment::find()->where(['id' => $dto->id])->one();

        if (!empty($assortment)) return null;

        $assortment = new Assortment();
        $assortment->id =        $dto->id;
        $assortment->name =      $dto->name;
        $assortment->article =   $dto->article;
        $assortment->stock =     $dto->stock;
        $assortment->reserve =   $dto->reserve;
        $assortment->inTransit = $dto->inTransit;
        $assortment->qty =       $dto->qty;
        $assortment->buyPrice =  $dto->buyPrice;
        if (!$assortment->save()) Error::error('$assortment->create', $assortment->getErrors());

        return $assortment;
    }
}
