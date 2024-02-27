<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "product_stock".
 *
 * @property int $id
 * @property string $sku
 * @property string $warehouse_name
 * @property int $promised
 * @property int $free
 *
 */
class ProductStock extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'product_stock';
    }


    public function rules()
    {
        return [
            [['sku', 'warehouse_name'], 'required'],
            [['sku', 'promised', 'free'], 'integer'],
            [['warehouse_name'], 'string', 'max' => 255],
        ];
    }

}
