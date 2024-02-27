<?php

namespace app\Order\Entity;


use app\Ozon\Entity\Stock;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $offer_id
 * @property float $price
 * @property int $status
 *
 */
class Product extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'product';
    }


    public function rules()
    {
        return [
            [['id', 'offer_id'], 'required'],
            [['id', 'status'], 'integer'],
            [['price'], 'number'],
            [['offer_id'], 'string', 'max' => 255],
        ];
    }


    public function getOrders()
    {
        return $this->hasMany(Order::class, ['product_id' => 'id']);
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['product_id' => 'id']);
    }

}
