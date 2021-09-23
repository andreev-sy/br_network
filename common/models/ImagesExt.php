<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property int $gorko
 * @property int $sort
 * @property string $realpath
 * @property string $subpath
 * @property string $waterpath
 * @property int $timestamp
 * @property string $type
 */
class ImagesExt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images_ext';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gorko_id', 'sort', 'path'], 'required'],
            [['gorko_id', 'sort', 'timestamp', 'event_id', 'room_id', 'rest_id'], 'integer'],
            [['path'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gorko_id' => 'Gorko',
            'sort' => 'Sort',
            'timestamp' => 'Timestamp',
        ];
    }
}
