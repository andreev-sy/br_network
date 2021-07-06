<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurants".
 *
 * @property int $id
 * @property int $gorko_id
 * @property string $name
 * @property string $address
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $price
 * @property string $cover_url
 */
class RestaurantsModule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gorko_id'], 'required'],
            [['gorko_id', 'unique_id', 'active'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gorko_id' => 'Gorko ID',
        ];
    }
}
