<?php

namespace app\Log\Entity;

use Yii;

/**
 * История всех действий
 *
 * @property int $id
 * @property int $account_id
 * @property int $user_id
 * @property int|null $object_id
 * @property int $type
 * @property string|null $info
 * @property string $create_at
 * @property int $status
 *
 * 101 - update ticker from quik
 *
 *
 */
class Log extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'log';
    }


    public function rules()
    {
        return [
            [['account_id', 'user_id', 'type'], 'required'],
            [['id', 'account_id', 'user_id', 'object_id', 'type', 'status'], 'integer'],
            [['info'], 'string'],
            [['create_at'], 'safe'],
        ];
    }


    // создать новую запись в  лог
    public static function create(int $account_id, int $type, $info = false)
    {
        date_default_timezone_set("Europe/Moscow");

        $log = new Log();

        if (!empty(Yii::$app->user->id)) {
            $log->user_id = Yii::$app->user->id;
        } else {
            $log->user_id = 1; // CRON
        }
        $log->account_id = $account_id;
        $log->type = $type;
        if (!empty($info)) $log->info = json_encode($info, JSON_UNESCAPED_UNICODE);
        $log->create_at = date('Y-m-d H:i:s');
        $log->status = 1;

        if (!empty(Yii::$app->user->id)) $log->user_id = Yii::$app->user->id;

        if (!$log->save()) Error::error('$log->new', $log->getErrors());

        return $log;
    }

}
