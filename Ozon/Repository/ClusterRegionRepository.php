<?php

namespace app\Ozon\Repository;

use app\Ozon\Entity\Region;

class ClusterRegionRepository
{

    // Все товары
    public function all(bool $asArray = false): ?array
    {
        $q = Region::find()->where(['is_delete' => 0]);

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id ASC')->all();
    }
}

