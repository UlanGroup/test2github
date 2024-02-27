<?php

namespace app\Order\Entity;

/**
 * This is the model class for table "funnel_ozon".
 *
 * @property int $id
 * @property string $date
 * @property int $view
 * @property int $visit
 * @property int $to_cart
 * @property int $to_order
 */

class FunnelOzon extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'funnel_ozon';
    }
    
    public function rules()
    {
        return [
            [['view', 'visit', 'to_cart', 'to_order'], 'integer'],
            [['date'], 'safe'],
        ];
    }


//    public function getTransactions()
//    {
//        return $this->hasMany(Transaction::class, ['order_id' => 'id']);
//    }
}
