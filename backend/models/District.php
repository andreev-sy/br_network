<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $name Наименование
 * @property int $agglomeration_id Агломерация
 *
 * @property CollectionDistrictVia[] $collectionDistrictVias
 * @property Agglomeration $agglomeration_id
 * @property DistrictRegionVia[] $districtRegionVias
 * @property Venues[] $venues
 */
class District extends \yii\db\ActiveRecord
{
    public $scenario;
    public $region_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'agglomeration_id'], 'required'],
            [['name'], 'string'],
            [['agglomeration_id'], 'integer'],
            [['agglomeration_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agglomeration::className(), 'targetAttribute' => ['agglomeration_id' => 'id']],
            [['region_ids'], 'safe'],
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
            'region_id' => Yii::t('app', 'Округ'),
            'region_ids' => Yii::t('app', 'Округа'),
        ];
    }

    /**
     * Gets query for [[CollectionDistrictVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionDistrictVias()
    {
        return $this->hasMany(CollectionDistrictVia::className(), ['district_id' => 'id']);
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

    /**
     * Gets query for [[DistrictRegionVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictRegionVias()
    {
        return $this->hasMany(DistrictRegionVia::className(), ['district_id' => 'id']);
    }

    /**
     * Gets query for [[Venues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenues()
    {
        return $this->hasMany(Venues::className(), ['district_id' => 'id']);
    }

    public static function getMap()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    public static function getMapViaRegion()
    {
        return ArrayHelper::map(Region::find()->with('districtRegionVias.district')->all(), 'name', function($r_data){
            return ArrayHelper::map(
                $r_data->districtRegionVias,
                function($r_via_data){ return $r_via_data->district->id; },
                function($r_via_data){ return $r_via_data->district->name; }
            );
        });
    }

    // public static function getMapViaCity()
    // {
    //     return ArrayHelper::map(Cities::find()->with('regions.districtRegionVias.district')->all(), 'name', function($c_data){
    //         return ArrayHelper::map(
    //             $c_data->regions,
    //             function($r_data){ return $r_data->name; },
    //             function($r_data){ 
    //                 return ArrayHelper::map(
    //                     $r_data->districtRegionVias,
    //                     function($r_via_data){ return $r_via_data->district->id; },
    //                     function($r_via_data){ return $r_via_data->district->name; }
    //                 );
    //             }
    //         );
    //     });
    // }

    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);
        
        if($this->scenario == 'update' or $this->scenario == 'create'){
            DistrictRegionVia::deleteAll([ 'district_id' => $this->id ]);

            if(!empty($this->region_ids)){
                foreach ($this->region_ids as $region_id) {
                    $via = new DistrictRegionVia();
                    $via->setAttributes([
                        'district_id' => $this->id,
                        'region_id' => $region_id,
                    ]);
                    $via->save();
                }
            }
        }
        

    }


}
