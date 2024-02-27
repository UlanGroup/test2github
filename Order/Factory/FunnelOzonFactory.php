<?php

namespace app\Order\Factory;

use app\Log\Entity\Error;
use app\Order\DTO\FunnelDTO;
use app\Order\Entity\FunnelOzon;

class FunnelOzonFactory
{
    // создать
    public function create(FunnelDTO $dto): ?FunnelOzon
    {
        /** @var FunnelOzon $funnel */
        $funnel = FunnelOzon::find()->where(['date' => $dto->date])->one();

        if (!empty($funnel)) {
            $funnel->date = $dto->date;
            $funnel->save();
            return $funnel;
        }

        $funnel = new FunnelOzon();
//        if (!empty($dto->id)) $funnel->id = $dto->id;
        $funnel->date = $dto->date;
        $funnel->view = $dto->view;
        $funnel->visit = $dto->visit;
        $funnel->to_cart = $dto->to_cart;
        $funnel->to_order = $dto->to_order;
        if (!$funnel->save()) Error::error('$product->create', $funnel->getErrors());

        return $funnel;
    }
}
