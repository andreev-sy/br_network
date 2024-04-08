<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_parking_type".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesParkingTypeVia[] $venuesParkingTypeVias
 */
class VenuesParkingType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_parking_type';
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
     * Gets query for [[VenuesParkingTypeVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesParkingTypeVias()
    {
        return $this->hasMany(VenuesParkingTypeVia::className(), ['venues_parking_type_id' => 'id']);
    }
}
