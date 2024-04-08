<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "collection_price_person".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 * @property int|null $min Минимальное значение
 * @property int|null $max Максимальное значение
 *
 * @property Collection[] $collections
 */
class CollectionPricePerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collection_price_person';
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

    /**
     * Gets query for [[Collections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['price_person_id' => 'id']);
    }
}
