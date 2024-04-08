<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_location".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsLocationVia[] $roomsLocationVias
 */
class RoomsLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_location';
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
     * Gets query for [[RoomsLocationVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLocationVias()
    {
        return $this->hasMany(RoomsLocationVia::className(), ['rooms_location_id' => 'id']);
    }
}
