<?php

namespace app\Finance\Entity;


/**
 * This is the model class for table "expense".
 *
 * @property int $ID
 * @property int|null $buying_id            Закупка
 * @property int|null $product_id
 * @property int|null $project_type         1 - общий, 2 - Ozon, 3 - Wb
 * @property int $type_id
 * @property int $qty
 * @property float $cost
 * @property int|null $user_id
 * @property string|null $create_at
 * @property int|null $status
 * @property int|null $del
 *
 *
 */
class Expense extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'expense';
    }


    public function rules()
    {
        return [
            [['project_type', 'type_id', 'qty', 'cost'], 'required'],
            [['buying_id', 'product_id', 'project_type', 'project_type', 'type_id', 'qty', 'user_id', 'status', 'del'], 'integer'],
            [['cost'], 'number'],
            [['create_at'], 'safe'],
        ];
    }


    public function del()
    {
        $this->del = 1;
        $this->save();
    }


}