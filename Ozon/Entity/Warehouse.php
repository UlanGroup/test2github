<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "ozon_warehouse".
 *
 * @property int $id
 * @property string $warehouse
 * @property string $cluster
 * @property int|null $del
 *
 */
class Warehouse extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ozon_warehouse';
    }


    public function rules()
    {
        return [
            [['warehouse', 'cluster'], 'required'],
            [['del'], 'integer'],
            [['warehouse', 'cluster'], 'string', 'max' => 255],
        ];
    }

}
