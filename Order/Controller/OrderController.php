<?php

namespace app\controllers;

use Yii;

use app\Order\Entity\Deal;
use app\Order\Entity\Order;
use app\Order\Entity\TempOrder;

use app\Order\Repository\DealRepository;
use app\Order\Repository\OptionRepository;
use app\Profit\Repository\ProfitRepository;

use app\Order\Factory\OrderFactory;

use app\Order\Service\OptionService;
use app\Service\DateTimeService;

use agielks\yii2\jwt\JwtBearerAuth;
use yii\filters\Cors;
use yii\web\Controller;
use yii\web\UploadedFile;

class OptionController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = ['class' => Cors::class];
        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class, 'optional' => ['test']];
        return $behaviors;
    }

    // --------- API ---------

    // Список
    public function actionList()
    {
        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->identity->account_id, false, true);

        $dealR = new DealRepository();
        $deals = $dealR->byAccount(Yii::$app->user->identity->account_id, true);

        $profitR = new ProfitRepository();
        $profit = $profitR->list(true);

        return ['options' => $options, 'deals' => $deals, 'profit' => $profit];
    }


    // Добавить позицию
    public function actionAddPosition()
    {
        $post = Yii::$app->getRequest()->post();

        $dto = OptionDTO::handle($post);

        $optionF = new OrderFactory();
        $optionF->create($dto);

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, false, true);

        return ['options' => $options];
    }


    // Добавить позицию
    public function actionAddPositionsMany()
    {
        $post = Yii::$app->getRequest()->post();

        $optionF = new OrderFactory();
        foreach ($post as $key => $item) {
            // получили массив строк
            // каждую строку превратим в ассоциативный массив
            $dto = OptionDTO::handle(json_decode($item, true));
            $opt = $optionF->create($dto);
            if (!empty($opt)) {
                $temp = TempOrder::find()->where(['account_id' => $opt->account_id, 'id' => $key])->one();
                $temp->setDone();
            }
        }

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, false, true);

        return ['options' => $options];
    }


    // Обновить позицию
    public function actionUpdatePosition()
    {
        $post = Yii::$app->getRequest()->post();

        $dto = OptionDTO::handle($post);

        $optionF = new OrderFactory();
        $optionF->update($dto);

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, false, true);

        return ['options' => $options];
    }


    // Закрыть позицию
    public function actionClosePosition()
    {
        $post = Yii::$app->getRequest()->post();

        $optionS = new OptionService();
        if ($post['close_qty'] > 1) {
            $optionS->closePositions($post);
        } else {
            $optionS->closePosition($post);
        }

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, true, true);

        $dealR = new DealRepository();
        $deals = $dealR->byAccount(Yii::$app->user->id, true);

        return ['options' => $options, 'deals' => $deals];
    }


    // Загрузка опционов из файла
    public function actionUpload()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $optionF = new OrderFactory();

        $files = UploadedFile::getInstancesByName('file');

        foreach ($files as $file) {
            $hash = sha1_file($file->tempName);
            $name = Yii::$app->user->id . '_' . substr($hash, 0, 12) . '.' . $file->extension;
            $path = './uploads/files';

            move_uploaded_file($file->tempName, "$path/$name");

            foreach (file('./uploads/files/' . $name) as $line) {
                // пропустить пустые строки
                if (empty($line) or strlen($line) < 1) {
                    continue;
                }

                $arr = explode(';', $line);
                $res = null;
                foreach ($arr as $a) {
                    $item = explode(':', $a);
                    if (!empty($item[0]) && !empty($item[1])) {
                        $res[trim($item[0])] = trim($item[1]);
                    }
                }

                // сохранить
                $dto = OptionDTO::handle($res);
                $optionF->create($dto);
            }
        }

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, true, true);

        return ['options' => $options, 'alert' => ['msg' => 'Загрузили', 'type' => 'success']];
    }


    // Загрузка опционов из txt файла созданного в самом Quik (портфель)
    public function actionUploadquik()
    {
        $post = Yii::$app->getRequest()->post();

        $files = UploadedFile::getInstancesByName('file');

        foreach ($files as $file) {
            $hash = sha1_file($file->tempName);
            $name = Yii::$app->user->id . '_' . substr($hash, 0, 12) . '.' . $file->extension;
            $path = './uploads/files';

            move_uploaded_file($file->tempName, "$path/$name");

            foreach (file('./uploads/files/' . $name) as $line) {

                // пропустить пустые строки
                if (empty($line) or strlen($line) < 1) {
                    continue;
                }

                $name = $strike = $type = $do = $expiration_at = null;

                $a = explode(',', $line);

                if ($a[3] == 'B') $do = 1;
                if ($a[3] == 'S') $do = 2;

                // опцион
                if (strlen($a[9]) > 5) {
                    // квартальный опцион Si81000BR3
                    if (strlen($a[9]) == 10) {
                        $strike = substr($a[9], 2, -3);
                        $type = substr($a[9], -2, -1);
                    }
                    // недельный опцион Si81000BR3B
                    if (strlen($a[9]) == 11) {
                        $strike = substr($a[9], 2, -4);
                        $type = substr($a[9], -3, -2);
                    }
                    if (in_array($type, ['F', 'R'])) $name = 'Si-6.23';
                    if (in_array($type, ['G', 'S', 'H', 'T', 'I', 'U'])) $name = 'Si-9.23';
                    if (in_array($type, ['L', 'X'])) $name = 'Si-12.23';

                    if (in_array($type, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'])) $type = 1;
                    if (in_array($type, ['M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X'])) $type = 2;

                    $expiration_at = DateTimeService::getThursday($a[13]);
                }

                // фьючерс
                if ($a[9] == 'SiM3') {
                    $name = 'Si-6.23';
                    $type = 3;
                    $expiration_at = '2023-06-15';
                }
                if ($a[9] == 'SiU3') {
                    $name = 'Si-9.23';
                    $type = 3;
                    $expiration_at = '2023-09-21';
                }
                if ($a[9] == 'SiZ3') {
                    $name = 'Si-12.23';
                    $type = 3;
                    $expiration_at = '2023-12-21';
                }

                $tempOrder = array(
                    'account_id' => Yii::$app->user->identity->account_id,
                    'deposit_id' => (int)$post['deposit_id'],
                    'trader_id' => 0,
                    'stock_id' => 0,
                    'orderId' => $a[0],
                    'bidId' => $a[1],
                    'date' => $a[13],
                    'time' => $a[2],
                    'expiration_at' => $expiration_at,
                    'n' => $a[9],
                    'price' => $a[10],
                    'qty' => $a[11],
                    'payment' => $a[12],
                    'bank' => $a[7],
                    'name' => $name,
                    'strike' => $strike,
                    'type' => $type,
                    'do' => $do,
                    'position' => 1,
                    'status' => 7,
                    'comment' => substr($a[4], 1)
                );

                TempOrder::create($tempOrder);
            }
        }

        $result = TempOrder::find()->where(['account_id' => Yii::$app->user->identity->account_id, 'status' => 1])->orderBy('create_at')->asArray()->all();

        return ['result' => $result, 'alert' => ['msg' => 'Загрузили', 'type' => 'success']];
    }


    // ОБЪЕДИНИТЬ В СДЕЛКУ
    public function actionSaveToDeal()
    {
        $post = Yii::$app->getRequest()->post();

        OptionService::unionPosition($post['ids']);

        $optionR = new OptionRepository();
        $options = $optionR->byAccount(Yii::$app->user->id, true, true);

        $dealR = new DealRepository();
        $deals = $dealR->byAccount(Yii::$app->user->id, true);

        return ['options' => $options, 'deals' => $deals];
    }


    // удалить опцион
    public function actionDelete()
    {
        $post = Yii::$app->getRequest()->post();

        if (empty($post['id'])) {
            return null;
        }

        $option = Order::find()->where(['user_id' => Yii::$app->user->id, 'id' => $post['id']])->one();
        if (!empty($option)) {
            $option->del = 1;
            $option->save();
        }
    }


    // перемещение опциона в другую сделку
    public function actionSort()
    {
        $post = Yii::$app->getRequest()->post();

        if (empty($post['option_id'])) {
            return null;
        }

        $option = Order::find()->where(['user_id' => Yii::$app->user->id, 'id' => $post['option_id']])->one();
        if (empty($option)) {
            return;
        }

        $from_deal_id = $option->deal_id;

        if (!empty($post['deal_id']) && (int)$post['deal_id'] > 0) {
            $option->deal_id = (int)$post['deal_id'];
        } else {
            $option->deal_id = null;
        }
        $option->save();

        // пересчитаем прибыль в сделках
        $deal_from = Deal::find()->where(['deal.id' => $from_deal_id, 'deal.bot_id' => $option->bot_id])->joinWith('options')->one();
        if (!empty($deal_from)) {
            $deal_from->reCountProfit();
        }

        $deal_to = Deal::find()->where(['deal.id' => $post['deal_id'], 'deal.bot_id' => $option->bot_id])->joinWith('options')->one();
        if (!empty($deal_to)) {
            $deal_to->reCountProfit();
        }
    }


    // убрать id сделки из опциона
    public function actionRemoveDeal()
    {
        $post = Yii::$app->getRequest()->post();

        if (empty($post['option_id'])) {
            return null;
        }

        $option = Order::find()->where(['user_id' => Yii::$app->user->id, 'id' => $post['option_id']])->one();
        if (!empty($option)) {
            // $deal_from = Deal::find()->where(['id' => $option->deal_id, 'uid' => Yii::$app->user->id])->one();
            // $deal_from->reCountProfit();

            $option->deal_id = null;
            $option->save();
        }
    }


    // добавить пустой результат
    public function actionAddResult()
    {
//        $dealF = new ResultFactory();
//        $dealF->create();
//
//        $dealR = new DealRepository();
//        $deals = $dealR->byUser(Yii::$app->user->id, true);
//
//        return ['deals' => $deals];
    }


    // перемещение ордера в другую сделку
    public function actionSortDeals()
    {
        $post = Yii::$app->getRequest()->post();

//        if (empty($post['deal_id']) && empty($post['result_id'])) {
//            return null;
//        }
//
//        $deal = Deal::find()->where(['id' => $post['deal_id'], 'user_id' => Yii::$app->user->id])->one();
//        if (!empty($deal)) {
//            $deal->result_id = $post['result_id'];
//            $deal->save();
//
//            $resultR = new ResultRepository();
//            $result_from = $resultR->one($deal->result_id);
//            $result_to = $resultR->one($post['result_id']);
//
//            ResultService::reCountProfit($result_from);
//            ResultService::reCountProfit($result_to);
//        }
    }


//    // Подсчет профита
//    public function actionProfit()
//    {
//        $portfolioR = new PortfolioRepository();
//        $portfolios = $portfolioR->list();
//
//        $dealR = new DealRepository();
//        $deals = $dealR->list();
//
//        $paymentR = new PaymentRepository();
//        $payments = $paymentR->list();
//
//        ProfitService::reCount(new ProfitFactory(), $portfolios, $deals, $payments);
//    }


    // ТЕХНИЧЕСКОЕ
    public function actionTest()
    {
        $dealR = new DealRepository();
        $deals = $dealR->byAccount(7, false);

        foreach ($deals as $deal) {
            $create_at = null;
            $done_at = null;
            foreach ($deal->options as $option) {
                if ($option->position == 1) {
                    $create_at = $option->create_at;
                }
                if ($option->position == 2) {
                    $done_at = $option->create_at;
                }
            }
            $deal->date = $create_at;
            $deal->done_at = $done_at;
            $deal->save();
        }
    }


}
