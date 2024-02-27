<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "ms_assortment".
 *
 * @property int $id
 * @property string $cluster
 * @property string $warehouse
 *
 */
class AssortmentWarehouse extends \yii\db\ActiveRecord
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
