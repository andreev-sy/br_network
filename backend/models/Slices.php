<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property string $alias
 * @property string $h1
 * @property string $title
 * @property string $description
 * @property string $params
 * @property string $img_alt
 * @property integer $also_looking
 */

class Slices extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'slices';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['alias', 'h1', 'params'], 'required'],
			[['alias', 'h1', 'title', 'description', 'params', 'keywords', 'text_top', 'text_bottom', 'img_alt', 'feature'], 'string'],
			// [['also_looking'], 'integer']
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
			'h1' => 'H1',
			'title' => 'Title',
			'description' => 'Description',
			'params' => 'Params',
			'keywords' => 'keywords',
			'text_top' => 'text_top',
			'text_bottom' => 'text_bottom',
			'feature' => 'Feature',
			// 'also_looking' => 'Также ищут'
		];
	}
}