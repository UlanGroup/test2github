<?php

namespace app\Log\Entity;

/**
 * This is the model class for table "error".
 *
 * @property int $id
 * @property string $url
 * @property string $info
 * @property string $create_at
 * @property int $status            1 info, 2 warning, 3 error
 */
class Error extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'error';
    }

    public function rules()
    {
        return [
            [['url'], 'required'],
            [['info'], 'string'],
            [['create_at'], 'safe'],
            [['status'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }


    public static function info($url, $info = null)
    {
        $new = new Error();
        $new->url = $url;
        if (!empty($info)) {
            $new->info = json_encode($info, JSON_UNESCAPED_UNICODE);
        }
        $new->status = 1; // 1 info, 2 warning, 3 error
        $new->save();
    }


    public static function error($url, $info = null)
    {
        $new = new Error();
        $new->url = $url;
        if (!empty($info)) {
            $new->info = json_encode($info, JSON_UNESCAPED_UNICODE);
        }
        $new->status = 3; // 1 info, 2 warning, 3 error
        $new->save();
    }

}
