<?php

namespace app\Ozon\Entity;

/**
 * This is the model class for table "category_ozon".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 *
 */
class Category extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'category_ozon';
    }


    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
