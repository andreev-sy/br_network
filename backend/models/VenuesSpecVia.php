<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_spec_via".
 *
 * @property int $id
 * @property int $venue_id
 * @property int $venues_spec_id
 *
 * @property Venues $venue
 * @property VenuesSpec $venuesSpec
 */
class VenuesSpecVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_spec_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'venues_spec_id'], 'required'],
            [['venue_id', 'venues_spec_id'], 'integer'],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['venues_spec_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesSpec::className(), 'targetAttribute' => ['venues_spec_id' => 'id']],
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
            'venues_spec_id' => Yii::t('app', 'Venues Spec ID'),
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
     * Gets query for [[VenuesSpec]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpec()
    {
        return $this->hasOne(VenuesSpec::className(), ['id' => 'venues_spec_id']);
    }
}
