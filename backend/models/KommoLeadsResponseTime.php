<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kommo_leads_response_time".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 * @property int|null $min Минимальное значение
 * @property int|null $max Максимальное значение
 */
class KommoLeadsResponseTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kommo_leads_response_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text', 'text_ru'], 'string'],
            [['min', 'max'], 'integer'],
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
            'min' => Yii::t('app', 'Минимальное значение'),
            'max' => Yii::t('app', 'Максимальное значение'),
        ];
    }
}
