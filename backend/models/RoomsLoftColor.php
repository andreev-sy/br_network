<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_color".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLoftColorVia[] $roomsLoftColorVias
 */
class RoomsLoftColor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_color';
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
     * Gets query for [[RoomsLoftColorVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftColorVias()
    {
        return $this->hasMany(RoomsLoftColorVia::className(), ['rooms_loft_color_id' => 'id']);
    }
}
