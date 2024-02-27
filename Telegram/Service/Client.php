<?php

namespace app\Telegram\Service;

use app\Log\Entity\Error;
use app\Order\Entity\OrderOzon;
use app\Telegram\Entity\Noti;
use app\User\Entity\User;
use Telegram\Bot\Api;


class Client
{
    const TOKEN = '6551751089:AAGdK_40Nk5zT0eB4JWBT6akn0spnFOp-7s';


    // новый юзер
    public static function new($token, $result)
    {
        Error::info('Telegram', $result);
        $fid = self::Fid($result);

//        $callback_id = self::CallbackId($result);
//        $message_id = self::MessageId($result);
//        $text = self::Text($result);

        Send::Text($token, $fid, "Привет!\n\nЯ бот UlanGroup!", null, null);
    }


    public static function textMe($text)
    {
        Send::Text(self::TOKEN, 200783233, $text, null, null);// Уланенко
        Send::Text(self::TOKEN, 406209800, $text, null, null);// Потапов
    }


    public static function dayReport()
    {
        $date = date('Y-m-d', strtotime("-1 day"));
        $orders = OrderOzon::find()->where(['DATE(created_at)' => $date])->asArray()->all();

        $result = ['qty' => 0, 'revenue' => 0, 'count' => 0, 'cancel' => 0];
        foreach ($orders as $order) {
            if ($order['status'] == 5) {
                $result['cancel']++;
            } else {
                $result['count']++;
                $result['qty'] += $order['qty'];
                $result['revenue'] += (float)$order['price'];
            }
        }

        $text = "Отчет за " . date('d.m.Y', strtotime($date)) .
            "\n👍 Оборот: " . $result['revenue'] .
            "\n📦 Заказов: " . $result['count'] .
            "\n👠 Товаров: " . $result['qty'] .
            "\n🚫 Отмен: " . $result['cancel'];

        self::textMe($text);// Уланенко
    }





    // Вебхук
    // https://api.telegram.org/bot6551751089:AAGdK_40Nk5zT0eB4JWBT6akn0spnFOp-7s/setWebhook?url=https://api.ulangroup.ru/site/telegram
    // https://api.telegram.org/bot6551751089:AAGdK_40Nk5zT0eB4JWBT6akn0spnFOp-7s/getWebhookInfo
    public static function Webhook($token)
    {
        $telegram = new Api($token);
        return $telegram->setWebhook(['url' => 'https://api.ulangroup.ru/site/telegram']);
    }


    // FID (user_id из telegram)
    public static function Fid($result)
    {
        if (!empty($result->callback_query->message->chat->id)) {
            $fid = $result->callback_query->message->chat->id;
        } else {
            $fid = $result->message->chat->id;
        }
        return $fid;
    }


    public static function CallbackId($result)
    {
        if (!empty($result->callback_query)) {
            return $result->callback_query->id;
        }
    }


    public static function MessageId($result)
    {
        if (!empty($result->callback_query)) {
            return $result->callback_query->message->message_id;
        }
    }


    // Текст
    public static function Text($result)
    {
        if (!empty($result->callback_query)) {
            if (!empty($result->callback_query->message->text)) {
                return $result->callback_query->message->text;
            }
        } else {
            if (!empty($result->message->text)) {
                return $result->message->text;
            }
        }
    }



//    // Кнопки
//    public static function Call($result)
//    {
//        if (!empty($result->callback_query->data)) {
//            return Callback::Call(2, $result->callback_query->data);
//        }
//    }


    // найти fid
    public static function fidByUserId($user_id)
    {
        $profile = User::find()->where(['id' => $user_id])->one();
        if (!empty($profile) && !empty($profile->tl)) {
            return $profile->tl;
        }
        return null;
    }


    // найти профиль
    public static function Profile($fid)
    {
        return User::find()->where(['tl' => $fid])->one();
    }

}
