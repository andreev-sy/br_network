<?php

namespace common\models\premium;

use Yii;

class PhoneClicks extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'phone_clicks';
    }

    public function rules()
    {
        return [
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['premium_rest_id', 'count'], 'integer'],
        ];
    }
}
