<?php

namespace app\Telegram\Service;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

/**
 * ТИПЫ ВЛОЖЕНИЙ
 * 1 Photo
 * 3 Audio
 * 5 Doc
 * 7 Video
 */
class Send
{

    // Отправка текста
    public static function Text($token, $fid, $msg, $image, $btn)
    {
        $key = null;
        if (!empty($btn)) {
            $key = self::Button($btn);
        }
        if (!empty($image)) {
            self::sendPhoto($token, $fid, $image);
        }
        self::sendText($token, $fid, $msg, $key);
    }


    // Отправить фото
    public static function sendPhoto($token, $fid, $image)
    {
        try {
            $telegram = new Api($token);
            return $telegram->sendPhoto([
                'chat_id' => $fid,
                'photo' => $image
            ]);
        } catch (TelegramResponseException $e) {
            $error = $e->getResponseData();
            if (!empty($error)) {
                return $error;
            }
        }
    }


    // Отправить в Tl
    public static function sendText($token, $fid, $msg, $key)
    {
        try {
            $telegram = new Api($token);
            return $telegram->sendMessage([
                'chat_id' => $fid,
                'text' => $msg,
                'parse_mode' => 'HTML',
                'reply_markup' => $key
            ]);
        } catch (TelegramResponseException $e) {
            $error = $e->getResponseData();
            if (!empty($error)) {
                return $error;
            }
        }
    }


    // Клавиатура Telegram
    private static function Button($btns): ?string
    {
        if (empty($btns)) {
            return null;
        }

        $btns = json_decode($btns);
        $m = null;
        $a = 0;
        foreach ($btns as $b3) {
            $k0 = null;
            foreach ($b3 as $b2) {

                $code = $b2[1];

                // url в кнопке = четвертый параметр
                if (!empty($b2[3])) {
                    $k0[] = array("text" => $b2[0], "url" => $b2[3], "callback_data" => "a" . ++$a . "_" . $code);
                } else {
                    $k0[] = array("text" => $b2[0], "callback_data" => "a" . ++$a . "_" . $code);
                }
            }
            $m[] = $k0;
        }

        $key = array("inline_keyboard" => $m,);

        return json_encode($key);
    }


}