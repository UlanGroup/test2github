<?php

namespace app\Order\Repository;

use app\Order\Entity\ClusterRegion;

class ClusterRegionRepository
{

    // Все товары
    public function all(bool $asArray = false): ?array
    {
        $q = ClusterRegion::find();

        if (!empty($asArray)) $q->asArray();

        return $q->orderBy('id DESC')->all();
    }
}
