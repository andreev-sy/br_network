<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_venues_spec_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $venues_spec_id
 *
 * @property Rooms $room
 * @property VenuesSpec $venuesSpec
 */
class RoomsVenuesSpecVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_venues_spec_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'venues_spec_id'], 'required'],
            [['room_id', 'venues_spec_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
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
            'room_id' => Yii::t('app', 'Room ID'),
            'venues_spec_id' => Yii::t('app', 'Venues Spec ID'),
        ];
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
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
