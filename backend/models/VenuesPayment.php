<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_payment".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesPaymentVia[] $venuesPaymentVias
 */
class VenuesPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_payment';
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
     * Gets query for [[VenuesPaymentVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesPaymentVias()
    {
        return $this->hasMany(VenuesPaymentVia::className(), ['venues_payment_id' => 'id']);
    }
}
