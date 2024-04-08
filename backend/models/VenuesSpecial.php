<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_special".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesSpecialVia[] $venuesSpecialVias
 */
class VenuesSpecial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_special';
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
     * Gets query for [[VenuesSpecialVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpecialVias()
    {
        return $this->hasMany(VenuesSpecialVia::className(), ['venues_special_id' => 'id']);
    }
}
