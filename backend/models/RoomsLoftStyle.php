<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_loft_style".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLoftStyleVia[] $roomsLoftStyleVias
 */
class RoomsLoftStyle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_loft_style';
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
     * Gets query for [[RoomsLoftStyleVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftStyleVias()
    {
        return $this->hasMany(RoomsLoftStyleVia::className(), ['rooms_loft_style_id' => 'id']);
    }

}
