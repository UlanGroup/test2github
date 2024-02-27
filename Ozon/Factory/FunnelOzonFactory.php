<?php

namespace app\Ozon\Factory;

use app\Log\Entity\Error;
use app\Order\Entity\Dashboard;
use app\Ozon\DTO\FunnelDTO;

class FunnelOzonFactory
{
    // добавить данные в dashboard
    public function create(FunnelDTO $dto): ?Funnel
    {
        /** @var Funnel $funnel */
        $funnel = Dashboard::find()->where(['date' => $dto->date])->one();

        if (empty($funnel)) return null;

        $funnel->view = $dto->view;
        $funnel->visit = $dto->visit;
        $funnel->to_cart = $dto->to_cart;
        $funnel->to_order = $dto->to_order;
        if (!$funnel->save()) Error::error('$dashboard->create', $funnel->getErrors());

        return $funnel;
    }
}
