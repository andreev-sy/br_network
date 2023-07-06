<?php

namespace common\models\premium;

use Yii;

class Channels extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'channels';
    }

    public function rules()
    {
        return [
            [['gorko_id'], 'integer'],
            [['name', 'email', 'email_desc'], 'string'],
        ];
    }
}
