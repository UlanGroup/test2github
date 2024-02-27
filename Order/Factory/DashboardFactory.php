<?php

namespace app\Order\Factory;

use app\Log\Entity\Error;
use app\Order\Entity\Dashboard;
use app\Ozon\DTO\FunnelDTO;

class DashboardFactory
{

    // создать запись
    public function create(array $array): ?Dashboard
    {
        /** @var Dashboard $dashboard */
        $dashboard = Dashboard::find()->where(['date' => $array['date']])->one();

        if (empty($dashboard)) {
            $dashboard = new Dashboard();
            $dashboard->date = $array['date'];
        }

        if (!empty($array['revenue'])) $dashboard->revenue = $array['revenue'];
        if (!empty($array['margin'])) $dashboard->margin = $array['margin'];
        if (!empty($array['solditems'])) $dashboard->solditems = $array['solditems'];
        if (!empty($array['average_check'])) $dashboard->average_check = $array['average_check'];
        if (!empty($array['returns'])) $dashboard->returns = $array['returns'];
        if (!empty($array['salesbycategory'])) $dashboard->salesbycategory = $array['salesbycategory'];
        if (!$dashboard->save()) Error::error('dashboard->create', $dashboard->getErrors());

        return $dashboard;
    }


    // добавить данные воронки в dashboard
    public function addFunnel(FunnelDTO $dto): ?Dashboard
    {
        /** @var Dashboard $dashboard */
        $dashboard = Dashboard::find()->where(['date' => $dto->date])->one();

        if (!empty($dashboard)) return null;

        $dashboard->view = $dto->view;
        $dashboard->visit = $dto->visit;
        $dashboard->to_cart = $dto->to_cart;
        if (!$dashboard->save()) Error::error('$dashboard->create', $dashboard->getErrors());

        return $dashboard;
    }
}

