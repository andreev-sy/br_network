<?php

use Yii;
namespace backend\models;

/**
 * This is the model class for table "item_content".
 *
 * @property int $id
 * @property int $gorko_id
 * @property string $text1
 * @property string $text2
 * @property string $text3
 */
class ItemContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			[['gorko_id'], 'required'],
			[['gorko_id'], 'integer'],
            [['gorko_id'], 'unique'],
			[['text1', 'text2', 'text3'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
			'gorko_id' => 'Gorko ID',
			'text1' => 'Текст 1',
			'text2' => 'Текст 2',
			'text3' => 'Текст 3',
        ];
    }
}
