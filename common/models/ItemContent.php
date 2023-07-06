<?php

use Yii;
namespace common\models;

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
	public static function tableName()
	{
		return 'item_content';
	}

	public function rules()
	{
		return [
			[['gorko_id'], 'required'],
			[['gorko_id'], 'integer'],
			[['gorko_id'], 'unique'],
			[['text1', 'text2', 'text3'], 'string'],
		];
	}

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