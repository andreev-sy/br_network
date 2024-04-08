<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_location".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesLocationVia[] $venuesLocationVias
 */
class VenuesLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_location';
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
     * Gets query for [[VenuesLocationVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesLocationVias()
    {
        return $this->hasMany(VenuesLocationVia::className(), ['venues_location_id' => 'id']);
    }
}
