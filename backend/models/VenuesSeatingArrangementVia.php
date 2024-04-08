<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_seating_arrangement_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_seating_arrangement_id
 *
 * @property Venues $venue
 * @property VenuesSeatingArrangement $venuesSeatingArrangement
 */
class VenuesSeatingArrangementVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_seating_arrangement_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_seating_arrangement_id'], 'required'],
            [['venue_id', 'venues_seating_arrangement_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_seating_arrangement_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesSeatingArrangement::className(), 'targetAttribute' => ['venues_seating_arrangement_id' => 'id']],
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
            'venues_seating_arrangement_id' => Yii::t('app', 'Venues Seating Arrangement ID'),
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
     * Gets query for [[VenuesSeatingArrangement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSeatingArrangement()
    {
        return $this->hasOne(VenuesSeatingArrangement::className(), ['id' => 'venues_seating_arrangement_id']);
    }
}
