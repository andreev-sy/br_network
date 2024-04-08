<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_equipment_interior".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLoftEquipmentInteriorVia[] $roomsLoftEquipmentInteriorVias
 */
class RoomsLoftEquipmentInterior extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_equipment_interior';
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
     * Gets query for [[RoomsLoftEquipmentInteriorVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipmentInteriorVias()
    {
        return $this->hasMany(RoomsLoftEquipmentInteriorVia::className(), ['rooms_loft_equipment_interior_id' => 'id']);
    }
}
