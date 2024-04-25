<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rooms_zones".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 *
 * @property RoomsZonesVia[] $roomsZonesVias
 */
class RoomsZones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms_zones';
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
     * Gets query for [[RoomsZonesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsZonesVias()
    {
        return $this->hasMany(RoomsZonesVia::className(), ['rooms_zones_id' => 'id']);
    }

    // public function beforeDelete()
    // {
    //     if (!parent::beforeDelete()) {
    //         return false;
    //     }

    //     RoomsZonesVia::deleteAll(['rooms_zones_id' => $this->id]);

    //     return true;
    // }

} 
