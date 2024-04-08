<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_special_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_special_id
 *
 * @property Venues $venue
 * @property VenuesSpecial $venuesSpecial
 */
class VenuesSpecialVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_special_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_special_id'], 'required'],
            [['venue_id', 'venues_special_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_special_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesSpecial::className(), 'targetAttribute' => ['venues_special_id' => 'id']],
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
            'venues_special_id' => Yii::t('app', 'Venues Special ID'),
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
     * Gets query for [[VenuesSpecial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpecial()
    {
        return $this->hasOne(VenuesSpecial::className(), ['id' => 'venues_special_id']);
    }
}
