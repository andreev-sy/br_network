<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_equipment_3".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLoftEquipment3Via[] $roomsLoftEquipment3Vias
 */
class RoomsLoftEquipment3 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_equipment_3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text', 'text_ru'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Текст'),
            'text_ru' => Yii::t('app', 'Текст (ру)'),
        ];
    }

    /**
     * Gets query for [[RoomsLoftEquipment3Vias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipment3Vias()
    {
        return $this->hasMany(RoomsLoftEquipment3Via::className(), ['rooms_loft_equipment_3_id' => 'id']);
    }
}
