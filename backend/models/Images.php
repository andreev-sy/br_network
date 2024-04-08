<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property int|null $venue_id Привязка к заведению
 * @property int|null $room_id Привязка к залу
 * @property string|null $realpath Путь к оригиналу на сервере
 * @property string|null $subpath Урл к оригиналу
 * @property string|null $webppath Урл к webp
 * @property string|null $waterpath Урл к картинке с ватермаркой
 * @property int $timestamp Временная метка
 * @property int $sort Сортировка
 *
 * @property Rooms $room
 * @property Venues $venue
 */
class Images extends \yii\db\ActiveRecord
{



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id', 'room_id', 'timestamp', 'sort'], 'integer'],
            [['timestamp', 'sort'], 'required'],
            [['realpath', 'subpath', 'webppath', 'waterpath'], 'string'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'venue_id' => Yii::t('app', 'Привязка к заведению'),
            'room_id' => Yii::t('app', 'Привязка к залу'),
            'realpath' => Yii::t('app', 'Путь к оригиналу на сервере'),
            'subpath' => Yii::t('app', 'Урл к оригиналу'),
            'webppath' => Yii::t('app', 'Урл к webp'),
            'waterpath' => Yii::t('app', 'Урл к картинке с ватермаркой'),
            'timestamp' => Yii::t('app', 'Временная метка'),
            'sort' => Yii::t('app', 'Сортировка'),
        ];
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
    }

    /**
     * Gets query for [[Venue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }



    
    public static function updateSortIndex($model)
	{
		$images = self::find()->where(['room_id' => $model->room_id])->orderBy(['sort' => SORT_ASC])->all();

		foreach ($images as $image) {
			if ($image->sort > $model->sort) {
				$image->sort -= 1;
				$image->save();
			}
		}
	}
}
