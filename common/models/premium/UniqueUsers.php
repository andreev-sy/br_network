<?php

namespace common\models\premium;

use Yii;

class UniqueUsers extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'unique_users';
    }

    public function rules()
    {
        return [
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['premium_rest_id', 'count'], 'integer'],
        ];
    }
}
