<?php

namespace app\Ozon\Repository;

use app\Ozon\Entity\Warehouse;

class WarehouseRepository
{

    // Все товары
    public function all(bool $asArray = false): ?array
    {
        $q = Warehouse::find()->where(['del' => 0]);

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id ASC')->all();
    }
}

