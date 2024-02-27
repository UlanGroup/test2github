<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "ozon_stock".
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string $offer_id            // item_code
 * @property string $warehouse_name
 * @property string $cluster
 * @property int $promised
 * @property int $free
 *
 */
class Stock extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ozon_stock';
    }


    public function rules()
    {
        return [
            [['sku', 'offer_id', 'warehouse_name'], 'required'],
            [['product_id', 'sku', 'promised', 'free'], 'integer'],
            [['offer_id', 'warehouse_name', 'cluster'], 'string', 'max' => 255],
        ];
    }

}
