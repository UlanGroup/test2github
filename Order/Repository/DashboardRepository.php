<?php

namespace app\Order\Repository;

use app\Order\Entity\Dashboard;

class DashboardRepository
{

    public function all(): ?array
    {
        return Dashboard::find()->orderBy('date')->asArray()->all();
    }

}
