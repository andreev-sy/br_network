<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_extra_services_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_extra_services_id
 *
 * @property Venues $venue
 * @property VenuesExtraServices $venuesExtraServices
 */
class VenuesExtraServicesVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_extra_services_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_extra_services_id'], 'required'],
            [['venue_id', 'venues_extra_services_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_extra_services_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesExtraServices::className(), 'targetAttribute' => ['venues_extra_services_id' => 'id']],
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
            'venues_extra_services_id' => Yii::t('app', 'Venues Extra Services ID'),
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
     * Gets query for [[VenuesExtraServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesExtraServices()
    {
        return $this->hasOne(VenuesExtraServices::className(), ['id' => 'venues_extra_services_id']);
    }
}
