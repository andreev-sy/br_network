<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_features_via".
 *
 * @property int $id
 * @property int $room_id
 * @property int $rooms_features_id
 *
 * @property Rooms $room
 * @property RoomsFeatures $roomsFeatures
 */
class RoomsFeaturesVia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_features_via';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'rooms_features_id'], 'required'],
            [['room_id', 'rooms_features_id'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['rooms_features_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsFeatures::className(), 'targetAttribute' => ['rooms_features_id' => 'id']],
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
            'rooms_features_id' => Yii::t('app', 'Rooms Features ID'),
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
     * Gets query for [[RoomsFeatures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsFeatures()
    {
        return $this->hasOne(RoomsFeatures::className(), ['id' => 'rooms_features_id']);
    }
}
