<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_staff".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLoftStaffVia[] $roomsLoftStaffVias
 */
class RoomsLoftStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_staff';
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
     * Gets query for [[RoomsLoftStaffVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftStaffVias()
    {
        return $this->hasMany(RoomsLoftStaffVia::className(), ['rooms_loft_staff_id' => 'id']);
    }
}
