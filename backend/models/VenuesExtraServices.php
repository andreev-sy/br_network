<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_extra_services".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesExtraServicesVia[] $venuesExtraServicesVias
 */
class VenuesExtraServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_extra_services';
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
     * Gets query for [[VenuesExtraServicesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesExtraServicesVias()
    {
        return $this->hasMany(VenuesExtraServicesVia::className(), ['venues_extra_services_id' => 'id']);
    }
}
