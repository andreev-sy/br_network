<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_equipment_interior_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_loft_equipment_interior_id
 *
 * @property Rooms $room
 * @property RoomsLoftEquipmentInterior $roomsLoftEquipmentInterior
 */
class RoomsLoftEquipmentInteriorVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_equipment_interior_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_loft_equipment_interior_id'], 'required'],
            [['room_id', 'rooms_loft_equipment_interior_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_loft_equipment_interior_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsLoftEquipmentInterior::className(), 'targetAttribute' => ['rooms_loft_equipment_interior_id' => 'id']],
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
            'rooms_loft_equipment_interior_id' => Yii::t('app', 'Rooms Loft Equipment Interior ID'),
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
     * Gets query for [[RoomsLoftEquipmentInterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipmentInterior()
    {
        return $this->hasOne(RoomsLoftEquipmentInterior::className(), ['id' => 'rooms_loft_equipment_interior_id']);
    }
}
