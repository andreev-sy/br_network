<?php

namespace common\models\premium;

use Yii;

class PremiumRest extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'premium_rest';
    }

    public function rules()
    {
        return [
            [['gorko_id', 'channel', 'active'], 'integer'],
        ];
    }
}
