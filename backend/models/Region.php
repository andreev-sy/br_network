<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name Наименование
 * @property int $agglomeration_id Агломерация
 *
 * @property CollectionRegionVia[] $collectionRegionVias
 * @property DistrictRegionVia[] $districtRegionVias
 * @property Agglomeration $agglomeration
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['agglomeration_id'], 'integer'],
            [['agglomeration_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agglomeration::className(), 'targetAttribute' => ['agglomeration_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Наименование'),
            'agglomeration_id' => Yii::t('app', 'Агломерация'),
        ];
    }

    /**
     * Gets query for [[CollectionRegionVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionRegionVias()
    {
        return $this->hasMany(CollectionRegionVia::className(), ['region_id' => 'id']);
    }

    /**
     * Gets query for [[DistrictRegionVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictRegionVias()
    {
        return $this->hasMany(DistrictRegionVia::className(), ['region_id' => 'id']);
    }

    /**
     * Gets query for [[Agglomeration]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgglomeration()
    {
        return $this->hasOne(Agglomeration::className(), ['id' => 'agglomeration_id']);
    }

    public static function getMap()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    // public static function getMapViaCity()
    // {
    //     return ArrayHelper::map(Cities::find()->with('regions')->all(), 'name', function($c_data){
    //         return ArrayHelper::map(
    //             $c_data->regions,
    //             function($r_data){ return $r_data->id; },
    //             function($r_data){ return $r_data->name; }
    //         );
    //     });
    // }

}
