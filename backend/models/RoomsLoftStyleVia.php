<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_style_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_loft_style_id
 *
 * @property Rooms $room
 * @property RoomsLoftStyle $roomsLoftStyle
 */
class RoomsLoftStyleVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_style_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_loft_style_id'], 'required'],
            [['room_id', 'rooms_loft_style_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_loft_style_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsLoftStyle::className(), 'targetAttribute' => ['rooms_loft_style_id' => 'id']],
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
            'rooms_loft_style_id' => Yii::t('app', 'Rooms Loft Style ID'),
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
     * Gets query for [[RoomsLoftStyle]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftStyle()
    {
        return $this->hasOne(RoomsLoftStyle::className(), ['id' => 'rooms_loft_style_id']);
    }
}
