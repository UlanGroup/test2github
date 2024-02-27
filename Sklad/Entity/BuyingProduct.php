<?php

namespace app\Sklad\Entity;

use app\Ozon\Entity\Product;

/**
 * This is the model class for table "buying_product".
 * @property int $buying_id
 * @property int $product_id
 * @property float $price
 * @property int $qty
 * @property int $accept_qty
 * @property int $status
 */
class BuyingProduct extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'buying_product';
    }


    public function rules()
    {
        return [
            [['buying_id', 'product_id', 'qty', 'accept_qty', 'status'], 'integer'],
            [['price'], 'number'],
        ];
    }


    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}