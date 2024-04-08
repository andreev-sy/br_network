<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_kitchen_type".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesKitchenTypeVia[] $venuesKitchenTypeVias
 */
class VenuesKitchenType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_kitchen_type';
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
     * Gets query for [[VenuesKitchenTypeVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesKitchenTypeVias()
    {
        return $this->hasMany(VenuesKitchenTypeVia::className(), ['venues_kitchen_type_id' => 'id']);
    }
}
