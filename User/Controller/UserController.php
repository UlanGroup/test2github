<?php

namespace app\User\Controller;

use app\Bot\Factory\FileFactory;
use app\Log\Entity\Error;
use app\Service\ImageService;
use Yii;

use app\User\Entity\Menu;
use app\User\Entity\User;

use app\User\Repository\UserRepository;

use app\User\Factory\UserFactory;

use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;
use yii\web\UploadedFile;

class UserController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
        return $behaviors;
    }


    // Список пользователей
    public function actionList()
    {
        $userR = new UserRepository();
        $users = $userR->list();

        return ['alert' => ['msg' => 'Получили', 'type' => 'info'], 'users' => $users];
    }


    // получить меню пользователя
    public function actionMenu()
    {
        return ['menu' => Yii::$app->user->identity->menu];
    }


    public function actionProfile()
    {
        /** @var User $user */
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        return ['profile' => $user->forFront(), 'menu' => Menu::getMenu($user->role)];
    }


    // загрузить аватар
    public function actionUploadAvatar()
    {
        $userR = new UserRepository();
        $user = $userR->me();

        $imgS = new ImageService();
        $picture = $imgS->upload();

        $user->picture = $picture;
        if (!$user->save()) Error::error('$user->save', $user->getErrors());

        return ['alert' => ['msg' => 'Обновили', 'type' => 'info'], 'profile' => $user->forFront()];
    }


    // Создать юзера
//    public function actionCreateUser()
//    {
//        $post = Yii::$app->getRequest()->post();
//
//        if (empty($post) or empty($post['username']) or empty($post['email'])) {
//            return ['alert' => ['msg' => 'Недостаточно данных для регистрации', 'type' => 'error']];
//        }
//
//        $role = null;
//        if (!empty($post['role'])) {
//            $role = $post['role'];
//        }
//
//        $userF = new UserFactory();
//        $user = $userF->create($post['username'], $post['email'], Yii::$app->security->generateRandomString(8), $role);
//
//        if (empty($user)) {
//            return ['alert' => ['msg' => 'Ошибка', 'type' => 'error']];
//        }
//        $post['user_id'] = $user->id;
//
//        $profileF = new UserFactory();
//        $profileF->update($post);
//
//        $userR = new UserRepository();
//        $users = $userR->list();
//
//        return ['alert' => ['msg' => 'Пользователь создан', 'type' => 'success'], 'users' => $users];
//    }


//    // Сохранить юзера
//    public function actionSaveUser()
//    {
//        $post = Yii::$app->getRequest()->post();
//
//        if (empty($post) or empty($post['id'])) {
//            return ['alert' => ['msg' => 'Недостаточно данных', 'type' => 'error']];
//        }
//
//        $profileF = new UserFactory();
//        $profileF->update($post);
//
//        $userR = new UserRepository();
//        $users = $userR->list();
//
//        return ['alert' => ['msg' => 'Пользователь создан', 'type' => 'success'], 'users' => $users];
//    }

}
