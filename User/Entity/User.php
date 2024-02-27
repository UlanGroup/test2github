<?php

namespace app\User\Entity;

use app\Service\ParseService;
use Yii;

use app\DTO\CabinetDTO;
use app\Factory\CabinetFactory;
use VK\Client\VKApiClient;

use app\Entity\Error;
use app\Entity\Token;

use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string|null $roles
 * @property string|null $role
 * @property string|null $accounts
 * @property int|null $account_id
 * @property string $name
 * @property string|null $secondname
 * @property string|null $middlename
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $bday
 * @property int|null $sex
 * @property int|null $vk
 * @property int|null $tl
 * @property string|null $utm
 * @property string|null $picture
 * @property string $create_at
 * @property int $status            10 - активный
 *
 * ROLE
 * An - admin
 * Or - owner
 * Bs - Старший бюджетного отдела
 * Bo - бюджетный отдел
 * Ls - Старший юрист
 * Lr - юрист
 * Ur - простой юзер без роли
 *
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }


    public function rules()
    {
        return [
            [['username', 'name'], 'required'],
            [['id', 'vk', 'tl', 'sex', 'status'], 'integer'],
            [['bday', 'create_at'], 'safe'],
            [['utm'], 'string', 'max' => 8],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'roles', 'role', 'name', 'secondname', 'middlename', 'email', 'phone', 'picture'], 'string', 'max' => 255],
        ];
    }


    /**
     * Подготовить массив полей юзера для фронта
     */
    public function forFront()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'roles' => json_decode($this->roles),
            'r' => $this->role,
            'name' => $this->name,
            'secondname' => $this->secondname,
            'email' => $this->email,
            'phone' => $this->phone,
            'vk' => $this->vk,
            'tl' => $this->tl,
            'picture' => $this->picture,
            'sex' => $this->sex,
            'status' => $this->status
        ];
    }


    /**
     * Проверка прав доступа пользователя к этому URL
     */
    public function permission($url)
    {
        // $menu = array_column(Menu::find()->where(['role' => $this->role])->asArray()->all(), 'url');
        $menu = array_column(Yii::$app->user->identity->menu, 'url');
        if (in_array($url, $menu)) return true;
        return null;
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['user.id' => $token->claims()->get('id')])->one();
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /** @var User */
        return static::find()->where(['user.username' => $username, 'user.status' => 10])->joinWith('menu')->one();
        // return static::findOne(['username' => $username, 'status' => 10]);
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    public function getMenu()
    {
        return $this->hasMany(Menu::class, ['role' => 'role'])->onCondition(['menu.status' => 1]);
    }

}
