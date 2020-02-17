<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "filter".
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $type
 * @property string $source
 * @property int $sort
 */
class Filter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'name', 'type', 'source'], 'required'],
            [['source', 'alias', 'name', 'type'], 'string'],
            [['sort'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'name' => 'Name',
            'type' => 'Type',
            'source' => 'Source',
            'sort' => 'Сортировка',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(FilterItems::className(), ['filter_id' => 'id']);
    }
}
