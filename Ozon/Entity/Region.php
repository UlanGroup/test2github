<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "ozon_region".
 *
 * @property int $id
 * @property string $region
 * @property string $cluster
 * @property int|null $del
 */
class Region extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ozon_region';
    }


    public function rules()
    {
        return [
            [['region', 'cluster'], 'required'],
            [['del'], 'integer'],
            [['region', 'cluster'], 'string', 'max' => 255],
        ];
    }

}
