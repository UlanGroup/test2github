<?php

namespace app\Finance\Entity;


/**
 * This is the model class for table "expense_type".
 *
 * @property int $id
 * @property int|null $name
 *
 *
 */
class ExpenseType extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'expense_type';
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string'],
        ];
    }

}