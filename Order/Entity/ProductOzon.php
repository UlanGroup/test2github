<?php

namespace app\Order\Entity;


use app\Ozon\Entity\Category;

/**
 * This is the model class for table "product_ozon".
 *
 * @property int $id
 * @property string $offer_id
 * @property int $sku
 * @property int $category_id
 * @property float $price
 * @property string $name
 * @property string $barcode
 * @property int $status
 *
 */
class ProductOzon extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'product_ozon';
    }


    public function rules()
    {
        return [
            [['offer_id'], 'required'],
            [['id', 'sku', 'category_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['offer_id', 'name', 'barcode'], 'string', 'max' => 255],
        ];
    }


    public function getOrders()
    {
        return $this->hasMany(Order::class, ['product_id' => 'id']);
    }

//    public function getStocks()
//    {
//        return $this->hasMany(ProductStock::class, ['product_id' => 'id']);
//    }

}
