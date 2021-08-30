<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $gorko_id
 * @property int $name
 * @property int $restaurant_id
 * @property int $price
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $type
 * @property string $type_name
 */
class RoomsModule extends \yii\db\ActiveRecord
{

    public $admin_flag = false;

    public static function tableName()
    {
        return 'rooms';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'unique_id', 'active'], 'integer'],
            [['text'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Gorko ID',
        ];
    }
}
