<?php

namespace app\Ozon\Entity;


/**
 * This is the model class for table "ms_assortment".
 *
 * @property?string $id,
 * @property?string $name,
 * @property?string $article,
 * @property?int    $stock,
 * @property?int    $reserve,
 * @property?int    $inTransit,
 * @property?int    $qty,
 * @property?int    $buyPrice,
 *
 *
 */
class Assortment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'ms_assortment';
    }

    public function rules()
    {
        return [
//            [['id'], 'string', 'max' => 255, 'required'],
            [['id', 'name', 'article'], 'string', 'max' => 255],
            [['stock', 'reserve', 'inTransit', 'qty', 'buyPrice'], 'number'],
        ];
    }

}
