<?php

namespace backend\models;

use Yii;
use himiklab\sortablegrid\SortableGridBehavior;

/**
 * This is the model class for table "collection_venue_via".
 *
 * @property int $id
 * @property int $collection_id Подборка
 * @property int $venue_id Заведение
 * @property int $sort Сортировка
 * @property int $active Активно
 *
 * @property Collection $collection
 * @property Venues $venue
 */
class CollectionVenueVia extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'sort'
            ],
        ]; 
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collection_venue_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['collection_id', 'venue_id', 'sort'], 'required'],
            [['collection_id', 'venue_id', 'sort', 'active'], 'integer'],
            [['collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collection::className(), 'targetAttribute' => ['collection_id' => 'id']],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'collection_id' => Yii::t('app', 'Подборка'),
            'venue_id' => Yii::t('app', 'Заведение'),
            'sort' => Yii::t('app', 'Сортировка'),
            'active' => Yii::t('app', 'Активно'),
        ];
    }

    /**
     * Gets query for [[Collection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id' => 'collection_id']);
    }

    /**
     * Gets query for [[Venue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }
}
