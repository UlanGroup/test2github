<?php

namespace app\User\Entity;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $role
 * @property string $name
 * @property string $url
 * @property string $status
 *
 * ROLE
 * An - admin
 * Or - owner
 * Mn - manager
 *
 */
class Menu extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return 'menu';
    }


    public function rules()
    {
        return [
            [['id', 'role', 'name', 'url'], 'required'],
            [['id', 'status'], 'integer'],
            [['role', 'name', 'url'], 'string', 'max' => 255],
        ];
    }


    /**
     * Получить меню для роли
     */
    public static function getMenu($role)
    {
        return Menu::find()->select(['name', 'url'])->where(['role' => $role, 'status' => 1])->asArray()->all();
    }

}
