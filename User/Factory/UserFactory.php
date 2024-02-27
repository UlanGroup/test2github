<?php

namespace app\User\Factory;

use Yii;

use app\Log\Entity\Error;
use app\User\DTO\UserDTO;
use app\User\Entity\User;

class UserFactory
{
    public function create(string $username, string $email, string $password, string $role = 'Mn', ?string $name, ?string $secondname): ?User
    {
        /** @var User $user */
        $user = User::find()->where(['username' => $username])->one();

        if (!empty($user)) {
            return $user;
        }

        $user = new User();
        $user->role = $role;
//        $user->accounts = '[2]';
//        $user->account_id = 2;
        $user->username = $username;
        $user->name = $name;
        $user->secondname = $secondname;
        $user->auth_key = $password;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->email = $email;
        $user->status = 10;
        if (!$user->save()) Error::error('$user->save', $user->getErrors());

        return $user;
    }


    public function createVk(UserDTO $dto): ?User
    {
        /** @var User $user */
        $user = User::find()->where(['vk' => $dto->vk])->one();
        if (!empty($user)) return $user;

        $user = new User();
        $user->roles = '["Mn"]';
        $user->role = 'Mn';
//        $user->accounts = '[7]';
//        $user->account_id = 7;
        $user->username = $dto->username;
        $user->name = $dto->name;
        $user->secondname = $dto->secondname;
        $user->auth_key = Yii::$app->security->generateRandomString(8);
        $user->password_hash = Yii::$app->security->generatePasswordHash($user->auth_key);
        $user->email = $dto->email;
        $user->vk = $dto->vk;
        $user->tl = $dto->tl;
        $user->sex = $dto->sex;
        $user->picture = $dto->picture;
        $user->status = 10;
        if (!$user->save()) Error::error('$user->save', $user->getErrors());

        return $user;
    }


    public function update(array $post): ?User
    {
        /** @var User $user */
        $user = User::find()->where(['id' => $post['id']])->one();

        if (!empty($post['roles'])) $user->roles = $post['roles'];
        if (!empty($post['role'])) $user->role = $post['role'];
        if (!empty($post['name'])) $user->name = $post['name'];
        if (!empty($post['secondname'])) $user->secondname = $post['secondname'];
        if (!empty($post['middlename'])) $user->middlename = $post['middlename'];
        if (!empty($post['email'])) $user->email = trim($post['email']);
        if (!empty($post['phone'])) $user->phone = trim($post['phone']);
        if (!empty($post['tl'])) $user->tl = trim($post['tl']);
        if (!empty($post['picture'])) $user->picture = $post['picture'];
        if (!empty($post['status'])) $user->status = $post['status'];

        if (!$user->save()) Error::error('$user->save', $user->getErrors());

        return $user;
    }

}
