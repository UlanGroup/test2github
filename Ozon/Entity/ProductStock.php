<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "product_stock".
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $warehouse_name
 * @property int $promised
 * @property int $free
 *
 */
class Stock extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'product_stock';
    }


    public function rules()
    {
        return [
            [['product_id', 'sku', 'warehouse_name'], 'required'],
            [['product_id', 'sku', 'promised', 'free'], 'integer'],
            [['warehouse_name'], 'string', 'max' => 255],
        ];
    }

}
