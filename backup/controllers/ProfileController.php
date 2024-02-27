<?php

namespace app\controllers;

use Yii;

use app\User\Entity\Menu;
use app\User\Entity\User;
use agielks\yii2\jwt\JwtBearerAuth;

use yii\filters\Cors;
use yii\web\Controller;

class ProfileController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


//    public function actionProfile()
//    {
//        $profile = Profile::find()->where(['id' => Yii::$app->user->id])->joinWith('user')->asArray()->one();
//        return ['profile' => $profile, 'menu' => 'menu123'];
//    }


    public function actionRole()
    {
        $post = Yii::$app->getRequest()->post();

        $user = User::find()->where(['id' => Yii::$app->user->id])->one();

        if (in_array($post['role'], json_decode($user->roles))) {
            $user->role = $post['role'];
            $user->save();
        }

        return ['profile' => $user->userArray(), 'menu' => Menu::getMenu($user->role)];
    }

}