<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "cluster_warehouse".
 *
 * @property int $id
 * @property string $cluster
 * @property string $warehouse
 *
 */
class ClusterWarehouse extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'cluster_warehouse';
    }


    public function rules()
    {
        return [
            [['cluster', 'warehouse'], 'required'],
            [['cluster', 'warehouse'], 'string', 'max' => 255],
        ];
    }

}
