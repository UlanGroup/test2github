<?php

namespace app\Order\Entity;


/**
 * This is the model class for table "dashboard_metrics".
 *
 * @property int $id
 * @property string $date
 * @property int $revenue
 * @property int $margin
 * @property int $solditems
 * @property int $average_check
 * @property int $turnover
 * @property int $returns
 * @property float $conversion_rate
 * @property int $marketing_expenses
 * @property float $customer_satisfaction
 * @property string $salesbycategory            // с - сategory_id, r - revenue, s - solditems
 * @property int $view
 * @property int $visit
 * @property int $to_cart
 */
class Dashboard extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'dashboard_metrics';
    }


    public function rules()
    {
        return [
            [['date'], 'required'],
            [['revenue', 'margin', 'solditems', 'average_check', 'turnover', 'returns', 'marketing_expenses', 'view', 'visit', 'to_cart'], 'integer'],
            [['conversion_rate', 'customer_satisfaction'], 'number'],
            [['salesbycategory'], 'string'],
            [['date'], 'safe'],
        ];
    }
}
