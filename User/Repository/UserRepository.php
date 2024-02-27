<?php

namespace app\User\Repository;

use Yii;

use app\User\Entity\User;

class UserRepository extends \yii\db\ActiveRecord
{

    public function list(): ?array
    {
        return User::find()->select(['id', 'roles', 'role', 'name', 'secondname', 'picture', 'phone', 'email', 'tl', 'status'])
            ->where(['account_id' => Yii::$app->user->identity->account_id])->asArray()->all();
    }


    public function one(int $id, bool $asArray = false)
    {
        $q = User::find()->where(['id' => $id, 'account_id' => Yii::$app->user->identity->account_id]);

        if (!empty($asArray)) {
            $q->asArray();
        }

        return $q->one();
    }


    public function me(): ?User
    {
        /** @var User */
        return User::find()->where(['id' => Yii::$app->user->id])->one();
    }

}
