<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property int $city_id
 * @property int $line_id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 */
class MetroStations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metro_stations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'line_id'], 'required'],
            [['name', 'latitude', 'longitude'], 'string'],
            [['id', 'city_id', 'line_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

        ];
    }
}