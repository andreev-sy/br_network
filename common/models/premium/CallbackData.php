<?php

namespace common\models\premium;

use Yii;

class CallbackData extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'callback_data';
    }

    public function rules()
    {
        return [
            [['callback_id'], 'integer'],
            [['response'], 'string'],
        ];
    }
}
