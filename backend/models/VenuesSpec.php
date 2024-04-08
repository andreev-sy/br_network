<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_spec".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsVenuesSpecVia[] $roomsVenuesSpecVias
 * @property VenuesSpecVia[] $venuesSpecVias
 */
class VenuesSpec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_spec';
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
     * Gets query for [[RoomsVenuesSpecVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsVenuesSpecVias()
    {
        return $this->hasMany(RoomsVenuesSpecVia::className(), ['venues_spec_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesSpecVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpecVias()
    {
        return $this->hasMany(VenuesSpecVia::className(), ['venues_spec_id' => 'id']);
    }
}
