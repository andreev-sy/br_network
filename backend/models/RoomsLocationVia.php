<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_location_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_location_id
 *
 * @property Rooms $room
 * @property RoomsLocation $roomsLocation
 */
class RoomsLocationVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_location_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_location_id'], 'required'],
            [['room_id', 'rooms_location_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsLocation::className(), 'targetAttribute' => ['rooms_location_id' => 'id']],
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
            'rooms_location_id' => Yii::t('app', 'Rooms Location ID'),
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
     * Gets query for [[RoomsLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLocation()
    {
        return $this->hasOne(RoomsLocation::className(), ['id' => 'rooms_location_id']);
    }
}
