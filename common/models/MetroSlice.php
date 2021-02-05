<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property string $alias
 * @property int $active
 * @property int $restaurants_count
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $img_alt
 * @property string $h1
 * @property string $text_top
 * @property string $text_bottom
 */
class MetroSlice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metro_slice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id', 'city_id', 'line_id'], 'required'],
            [['alias', 'title', 'description', 'keywords', 'img_alt', 'h1', 'text_top', 'text_bottom'], 'string'],
            [['id', 'station_id', 'active', 'restaurants_count',],  'integer']
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

    public function getStation()
    {
        $station = $this->hasOne(MetroStations::className(), ['id' => 'station_id']);
        return $station;
    }

    public function getListForIndex($minCount = 0)
    {
        $stationList = MetroSlice::find()
            ->where(['active' => '1'])                
            ->andWhere('restaurants_count > :minCount', ['minCount' => $minCount])
            ->limit(27)
            ->asArray()
            ->all();

        return array_chunk($stationList, 9);
    }

    public function getListForAPI($minCount = 0)
    {
        $stationList = MetroSlice::find()
            ->where(['active' => '1'])                
            ->andWhere('restaurants_count > :minCount', ['minCount' => $minCount])
            ->with('station')
            ->all();

        return $stationList;
    }

}