<?php

namespace app\controllers;

use Yii;

use app\Telegram\Service\Client;

use app\Log\Entity\Error;
use app\User\Entity\Menu;
use app\User\Models\LoginForm;

use app\User\Entity\User;
use app\User\Models\UserRefreshToken;

use app\User\Factory\UserFactory;

use Telegram\Bot\Api;

use DateTimeImmutable;
use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;

class SiteController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = [
            'class' => JwtBearerAuth::class,
            'optional' => ['login', 'registration', 'index', 'telegram'],
        ];

        return $behaviors;
    }


    protected function verbs()
    {
        return [
            'login' => ['OPTIONS', 'POST'],
        ];
    }


    public function actionIndex()
    {
        return 'API';
    }


    public function actionLogin()
    {
        $model = new LoginForm();

//        $user = User::findOne(11);
//
//        $user->password_hash = Yii::$app->security->generatePasswordHash('pass');
//        if (!$user->save()) {
//            Error::add('login юзер save', $user->getErrors());
//        }

        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {

            /* @var $jwt \agielks\yii2\jwt\Jwt */

            $now = new DateTimeImmutable();
            $jwt = Yii::$app->get('jwt');

            $user = $model->getUser();

            if (empty($user)) return ['alert' => ['msg' => 'Нет такого пользователя', 'type' => 'error'], 'token' => null];

            $token = $jwt
                ->builder()
                // Configures the issuer (iss claim)
                ->issuedBy('https://api.ulangroup.ru/')
                // Configures the audience (aud claim)
                ->permittedFor('https://api.ulangroup.ru/')
                // Configures the id (jti claim)
                ->identifiedBy($user->id)
                // Configures the time that the token was issue (iat claim)
                ->issuedAt($now)
                // Configures the time that the token can be used (nbf claim)
                ->canOnlyBeUsedAfter($now)
                // Configures the expiration time of the token (exp claim)
                ->expiresAt($now->modify('+168 hour'))
                // Configures a new claim
                ->withClaim('id', $user->id)
                // Returns a signed token to be used
                ->getToken($jwt->signer(), $jwt->key())
                // Convert token to string
                ->toString();

            $this->generateRefreshToken($user);
        } else {
            return ['alert' => ['msg' => 'Неправильный логин или пароль', 'type' => 'error'], 'token' => null];
        }

        $model->validate();

        return ['alert' => ['msg' => 'С возвращением!', 'type' => 'success'], 'token' => $token ?? null, 'profile' => $user->forFront(), 'menu' => Menu::getMenu($user->role)];
    }


    // Регистрация
    public function actionRegistration()
    {
        $post = Yii::$app->getRequest()->post();

        if (empty($post) or empty($post['username']) or empty($post['email']) or empty($post['password'])) {
            return ['alert' => ['msg' => 'Недостаточно данных для регистрации', 'type' => 'error']];
        }

        $userF = new UserFactory();
        $user = $userF->create($post['username'], $post['email'], $post['password']);

        $model = new LoginForm();

        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            /* @var $jwt \agielks\yii2\jwt\Jwt */

            $now = new DateTimeImmutable();
            $jwt = Yii::$app->get('jwt');
            $user = $model->getUser();

            $token = $jwt
                ->builder()
                // Configures the issuer (iss claim)
                ->issuedBy('https://api.ulangroup.ru')
                // Configures the audience (aud claim)
                ->permittedFor('https://api.ulangroup.ru')
                // Configures the id (jti claim)
                ->identifiedBy($user->id)
                // Configures the time that the token was issue (iat claim)
                ->issuedAt($now)
                // Configures the time that the token can be used (nbf claim)
                ->canOnlyBeUsedAfter($now)
                // Configures the expiration time of the token (exp claim)
                ->expiresAt($now->modify('+168 hour'))
                // Configures a new claim
                ->withClaim('id', $user->id)
                // Returns a signed token to be used
                ->getToken($jwt->signer(), $jwt->key())
                // Convert token to string
                ->toString();

            $this->generateRefreshToken($user);
        }

        $model->validate();

        return ['token' => $token, 'profile' => $user];
    }


    public function actionRefreshToken()
    {
        $refreshToken = Yii::$app->request->cookies->getValue('refresh-token', false);
        if (!$refreshToken) {
            return new \yii\web\UnauthorizedHttpException('No refresh token found.');
        }

        $userRefreshToken = UserRefreshToken::findOne(['urf_token' => $refreshToken]);

        if (Yii::$app->request->getMethod() == 'POST') {
            // Getting new JWT after it has expired
            if (!$userRefreshToken) {
                return new \yii\web\UnauthorizedHttpException('The refresh token no longer exists.');
            }

            $user = User::find()  //adapt this to your needs
            ->where(['userID' => $userRefreshToken->urf_userID])
                ->andWhere(['not', ['usr_status' => 'inactive']])
                ->one();
            if (!$user) {
                $userRefreshToken->delete();
                return new \yii\web\UnauthorizedHttpException('The user is inactive.');
            }

            $token = $this->generateJwt($user);

            return [
                'status' => 'ok',
                'token' => (string)$token,
            ];

        } elseif (Yii::$app->request->getMethod() == 'DELETE') {
            // Logging out
            if ($userRefreshToken && !$userRefreshToken->delete()) {
                return new \yii\web\ServerErrorHttpException('Failed to delete the refresh token.');
            }

            return ['status' => 'ok'];
        } else {
            return new \yii\web\UnauthorizedHttpException('The user is inactive.');
        }
    }


    /**
     * @throws yii\base\Exception
     */
    private function generateRefreshToken(User $user, User $impersonator = null): UserRefreshToken
    {
        $refreshToken = Yii::$app->security->generateRandomString(200);

        // TODO: Don't always regenerate - you could reuse existing one if user already has one with same IP and user agent
        $userRefreshToken = new UserRefreshToken([
            'urf_userID' => $user->id,
            'urf_token' => $refreshToken,
            'urf_ip' => Yii::$app->request->userIP,
            'urf_user_agent' => Yii::$app->request->userAgent,
            'urf_created' => gmdate('Y-m-d H:i:s'),
        ]);
        if (!$userRefreshToken->save()) {
            throw new \yii\web\ServerErrorHttpException('Failed to save the refresh token: ' . $userRefreshToken->getErrorSummary(true));
        }

        // Send the refresh-token to the user in a HttpOnly cookie that Javascript can never read and that's limited by path
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'refresh-token',
            'value' => $refreshToken,
            'httpOnly' => true,
            'sameSite' => 'none',
            'secure' => true,
            'path' => '/v1/auth/refresh-token',  //endpoint URI for renewing the JWT token using this refresh-token, or deleting refresh-token
        ]));

        return $userRefreshToken;
    }


    // connect to telegram
    public function actionTelegram()
    {
        $token = '6551751089:AAGdK_40Nk5zT0eB4JWBT6akn0spnFOp-7s';

        $telegram = new Api($token);
        $result = $telegram->getWebhookUpdates();
        $res = json_decode($result);
        if (!empty($res)) {
            Client::new($token, $res);
            Error::info('Tl', $res);
        }

        return 'ok';
    }

}
