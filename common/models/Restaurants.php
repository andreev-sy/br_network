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
class Restaurants extends \yii\db\ActiveRecord
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
            [['gorko_id', 'name', 'address'], 'required'],
            [['gorko_id', 'min_capacity', 'max_capacity', 'price', 'district', 'parent_district', 'alcohol', 'firework', 'img_count'], 'integer'],
            [['name', 'address', 'cover_url', 'latitude', 'longitude', 'own_alcohol', 'cuisine', 'parking', 'extra_services', 'payment', 'special', 'phone', 'location'], 'string'],
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
            'name' => 'Name',
            'address' => 'Address',
            'min_capacity' => 'Min Capacity',
            'max_capacity' => 'Max Capacity',
            'price' => 'Price',
            'cover_url' => 'Cover Url',
        ];
    }

    public function getRooms(){
        return $this->hasMany(Rooms::className(), ['restaurant_id' => 'id']);
    }
}
