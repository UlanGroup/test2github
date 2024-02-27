<?php

namespace app\Order\Factory;

use app\Order\Entity\ClusterRegion;

class ClusterFactory
{

    // создать запись кластера в библиотеку
    public function create(array $array): ?ClusterRegion
    {
        /** @var ClusterRegion $pw */
        $pw = ClusterRegion::find()->where(['region' => $array['region'], 'cluster' => $array['cluster']])->one();
        if (!empty($pw)) {
            return $pw;
        }

        $pw = new ClusterRegion();
        $pw->region = $array['region'];
        $pw->cluster = $array['cluster'];
        $pw->save();

        return $pw;
    }

    // отредактировать кластер в библиотеке
    public function update(array $array): ?ClusterRegion
    {
        /** @var ClusterRegion $pw */
        $pw = ClusterRegion::find()->where(['id' => $array['id']])->one();

        $pw->region = $array['region'];
        $pw->cluster = $array['cluster'];
        $pw->save();

        return $pw;
    }

    // отредактировать кластер в библиотеке
    public function del(array $array): ?ClusterRegion
    {
        /** @var ClusterRegion $pw */
        $pw = ClusterRegion::find()->where(['id' => $array['id']])->one();

        $pw->del = 1;
        $pw->save();

        return $pw;
    }
}

