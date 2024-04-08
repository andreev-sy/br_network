<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_zones_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_zones_id
 *
 * @property Rooms $room
 * @property RoomsZones $roomsZones
 */
class RoomsZonesVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_zones_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_zones_id'], 'required'],
            [['room_id', 'rooms_zones_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_zones_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsZones::className(), 'targetAttribute' => ['rooms_zones_id' => 'id']],
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
            'rooms_zones_id' => Yii::t('app', 'Rooms Zones ID'),
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
     * Gets query for [[RoomsZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsZones()
    {
        return $this->hasOne(RoomsZones::className(), ['id' => 'rooms_zones_id']);
    }
}
