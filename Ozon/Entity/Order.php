<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "ozon_orders".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $created_at
 * @property string $city
 * @property string $delivery_type
 * @property int $is_premium
 * @property string $payment_type
 * @property string $region
 * @property string $warehouse_name
 * @property string $order_number
 * @property string $posting_number
 * @property string $offer_id
 * @property string $cluster
 * @property float $price
 * @property int $qty
 * @property int $sku
 * @property float $commission
 * @property float $delivery_cost
 * @property float $refund_cost
 * @property int $status
 *
 * STATUS
 *
 * 1 awaiting_packaging — ожидает упаковки
 * 2 awaiting_deliver — ожидает отгрузки
 * 3 delivering — доставляется
 * 4 delivered — доставлено
 * 5 cancelled — отменено
 *
 */
class Order extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ozon_orders';
    }


    public function rules()
    {
        return [
            [['order_id', 'offer_id', 'price', 'qty'], 'required'],
            [['order_id', 'product_id', 'is_premium', 'qty', 'sku', 'status'], 'integer'],
            [['price', 'commission', 'delivery_cost', 'refund_cost'], 'number'],
            [['offer_id', 'city', 'delivery_type', 'payment_type', 'region', 'warehouse_name', 'order_number', 'posting_number', 'offer_id', 'cluster'], 'string', 'max' => 255],
            [['created_at'], 'safe'],
        ];
    }


    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
