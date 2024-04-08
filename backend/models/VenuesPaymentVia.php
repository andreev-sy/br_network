<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_payment_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_payment_id
 *
 * @property Venues $venue
 * @property VenuesPayment $venuesPayment
 */
class VenuesPaymentVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_payment_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_payment_id'], 'required'],
            [['venue_id', 'venues_payment_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesPayment::className(), 'targetAttribute' => ['venues_payment_id' => 'id']],
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
            'venues_payment_id' => Yii::t('app', 'Venues Payment ID'),
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
     * Gets query for [[VenuesPayment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesPayment()
    {
        return $this->hasOne(VenuesPayment::className(), ['id' => 'venues_payment_id']);
    }
}
