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
            [['gorko_id', 'sort', 'realpath', 'type'], 'required'],
            [['gorko_id', 'sort', 'timestamp', 'item_id'], 'integer'],
            [['realpath', 'subpath', 'waterpath', 'type'], 'string'],
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
            'realpath' => 'Realpath',
            'subpath' => 'Subpath',
            'waterpath' => 'Waterpath',
            'timestamp' => 'Timestamp',
            'type' => 'Type',
        ];
    }
}
