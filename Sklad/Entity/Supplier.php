<?php

namespace app\Sklad\Entity;

/**
 * This is the model class for table "supplier".
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property int|null $status
 *
 * СТАТУСЫ
 * 0 - выключен
 * 1 - активен
 *
 */
class Supplier extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'supplier';
    }


    public function rules()
    {
        return [
            [['account_id', 'name'], 'integer'],
            [['account_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

}