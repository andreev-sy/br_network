<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_entrance_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_loft_entrance_id
 *
 * @property Rooms $room
 * @property RoomsLoftEntrance $roomsLoftEntranceVia
 */
class RoomsLoftEntranceVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_entrance_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_loft_entrance_id'], 'required'],
            [['room_id', 'rooms_loft_entrance_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_loft_entrance_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsLoftEntrance::className(), 'targetAttribute' => ['rooms_loft_entrance_id' => 'id']],
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
            'rooms_loft_entrance_id' => Yii::t('app', 'Rooms Loft Entrance Via ID'),
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
     * Gets query for [[RoomsLoftEntrance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEntrance()
    {
        return $this->hasOne(RoomsLoftEntrance::className(), ['id' => 'rooms_loft_entrance_id']);
    }
}
