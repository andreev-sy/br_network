<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_visit_status".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property VenuesVisit[] $venuesVisits
 */
class VenuesVisitStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_visit_status';
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
     * Gets query for [[VenuesVisits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesVisits()
    {
        return $this->hasMany(VenuesVisit::className(), ['status_id' => 'id']);
    }

}
