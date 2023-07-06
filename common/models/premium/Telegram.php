<?php

namespace common\models\premium;

use Yii;
use common\models\premium\PremiumRest;

class Telegram extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'telegram';
    }

    public function rules()
    {
        return [
            [['tg_user_id', 'premium_rest_id'], 'integer'],
        ];
    }

    public function getRest(){
        return $this->hasOne(PremiumRest::className(), ['id' => 'premium_rest_id']);
    }
}
