<?php

namespace common\models\premium;

use Yii;

class CallbackCount extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'callback_count';
    }

    public function rules()
    {
        return [
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['premium_rest_id', 'count'], 'integer'],
        ];
    }
}
