<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_seating_arrangement".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesSeatingArrangementVia[] $venuesSeatingArrangementVias
 */
class VenuesSeatingArrangement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_seating_arrangement';
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
     * Gets query for [[VenuesSeatingArrangementVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSeatingArrangementVias()
    {
        return $this->hasMany(VenuesSeatingArrangementVia::className(), ['venues_seating_arrangement_id' => 'id']);
    }
}
