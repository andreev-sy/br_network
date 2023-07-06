<?php

namespace common\models\premium;

use Yii;

class Calls extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'calls';
    }

    public function rules()
    {
        return [
            [['dt_start', 'dt_answer', 'dt_hangup'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['recording_id', 'restaurant_id', 'channel_id'], 'integer'],
            [['caller_phone', 'hangup_by', 'mp3'], 'string'],
        ];
    }
}
