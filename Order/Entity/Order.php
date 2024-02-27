<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $market_order_id
 * @property string $offer_id
 * @property float $price
 * @property int $quantity
 * @property string $region
 * @property string $warehouse
 * @property string $created
 * @property int $status
 *
 * STATUS
 *
 * 1 awaiting_packaging — ожидает упаковки
 * 2 awaiting_deliver — ожидает отгрузки
 * 3 delivering — доставляется
 * 41 delivered — доставлено
 * 5 cancelled — отменено
 *
 */
class Order extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'order';
    }


    public function rules()
    {
        return [
            [['market_order_id', 'offer_id', 'price', 'quantity'], 'required'],
            [['market_order_id', 'quantity', 'status'], 'integer'],
            [['price'], 'number'],
            [['offer_id', 'region', 'warehouse'], 'string', 'max' => 255],
            [['created'], 'safe'],
        ];
    }


//    public function getTransactions()
//    {
//        return $this->hasMany(Transaction::class, ['order_id' => 'id']);
//    }
}
