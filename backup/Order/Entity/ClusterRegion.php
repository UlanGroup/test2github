<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "cluster_region".
 *
 * @property int $id
 * @property string $cluster
 * @property string $region
 *
 */
class ClusterRegion extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'cluster_region';
    }


    public function rules()
    {
        return [
            [['cluster', 'region'], 'required'],
            [['cluster', 'region'], 'string', 'max' => 255],
        ];
    }

}
