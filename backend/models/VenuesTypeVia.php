<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_type_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_type_id
 *
 * @property Venues $venue
 * @property VenuesType $venuesType
 */
class VenuesTypeVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_type_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_type_id'], 'required'],
            [['venue_id', 'venues_type_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesType::className(), 'targetAttribute' => ['venues_type_id' => 'id']],
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
            'venues_type_id' => Yii::t('app', 'Venues Type ID'),
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
     * Gets query for [[VenuesType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesType()
    {
        return $this->hasOne(VenuesType::className(), ['id' => 'venues_type_id']);
    }
}
