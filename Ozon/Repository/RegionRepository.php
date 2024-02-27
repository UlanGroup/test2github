<?php

namespace app\Ozon\Repository;

use app\Ozon\Entity\Region;

class RegionRepository
{

    // Все регионы + кластеры
    public function all(bool $asArray = false): ?array
    {
        $q = Region::find()->where(['del' => 0]);

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id ASC')->all();
    }
}

