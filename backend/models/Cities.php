<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name Наименование
 * @property int $agglomeration_id Агломерация
 *
 * @property Collection[] $collections
 * @property Venues[] $venues
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
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
     * Gets query for [[Agglomeration]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgglomeration()
    {
        return $this->hasOne(Agglomeration::className(), ['id' => 'agglomeration_id']);
    }

    /**
     * Gets query for [[Collections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['city_id' => 'id']);
    }


    /**
     * Gets query for [[Venues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenues()
    {
        return $this->hasMany(Venues::className(), ['city_id' => 'id']);
    }

    public static function getMap()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
