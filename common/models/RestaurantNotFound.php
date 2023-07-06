<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_not_found".
 *
 * @property int $id
 */
class RestaurantNotFound extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'restaurant_not_found';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id'], 'required'],
			[['id'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [];
	}
}
