<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_equipment_3_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_loft_equipment_3_id
 *
 * @property Rooms $room
 * @property RoomsLoftEquipment3 $roomsLoftEquipment3
 */
class RoomsLoftEquipment3Via extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_equipment_3_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_loft_equipment_3_id'], 'required'],
            [['room_id', 'rooms_loft_equipment_3_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_loft_equipment_3_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsLoftEquipment3::className(), 'targetAttribute' => ['rooms_loft_equipment_3_id' => 'id']],
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
            'rooms_loft_equipment_3_id' => Yii::t('app', 'Rooms Loft Equipment 3 ID'),
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
     * Gets query for [[RoomsLoftEquipment3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipment3()
    {
        return $this->hasOne(RoomsLoftEquipment3::className(), ['id' => 'rooms_loft_equipment_3_id']);
    }
}
