<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rooms_premium".
 *
 * @property int $id
 */
class RoomsPremium extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'rooms_premium';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['gorko_id', 'start', 'finish'], 'required'],
			[['gorko_id', 'start', 'finish'], 'integer']
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
