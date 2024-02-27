<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "ozon_warehouse".
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
        return 'ozon_warehouse';
    }


    public function rules()
    {
        return [
            [['warehouse', 'cluster'], 'required'],
            [['warehouse', 'cluster'], 'string', 'max' => 255],
        ];
    }

}
