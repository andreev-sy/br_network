<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_parking_type_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_parking_type_id
 *
 * @property Venues $venue
 * @property VenuesParkingType $venuesParkingType
 */
class VenuesParkingTypeVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_parking_type_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_parking_type_id'], 'required'],
            [['venue_id', 'venues_parking_type_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_parking_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesParkingType::className(), 'targetAttribute' => ['venues_parking_type_id' => 'id']],
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
            'venues_parking_type_id' => Yii::t('app', 'Venues Parking Type ID'),
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
     * Gets query for [[VenuesParkingType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesParkingType()
    {
        return $this->hasOne(VenuesParkingType::className(), ['id' => 'venues_parking_type_id']);
    }
}
