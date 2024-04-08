<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_location_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_location_id
 *
 * @property Venues $venue
 * @property VenuesLocation $venuesLocation
 */
class VenuesLocationVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_location_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_location_id'], 'required'],
            [['venue_id', 'venues_location_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesLocation::className(), 'targetAttribute' => ['venues_location_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'venue_id' => Yii::t('app', 'Venue ID'),
            'venues_location_id' => Yii::t('app', 'Venues Location ID'),
        ];
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

    /**
     * Gets query for [[VenuesLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesLocation()
    {
        return $this->hasOne(VenuesLocation::className(), ['id' => 'venues_location_id']);
    }
}
