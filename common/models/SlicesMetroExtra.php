<?php

namespace common\models;

use Yii;

class SlicesMetroExtra extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
		return 'slices_metro_extra';
	}

	public function rules()
	{
		return [
			[['id', 'metro_table_id', 'slices_id', 'restaurant_count'], 'integer']
		];
	}

	public function attributeLabels()
	{
		return [];
	}

	public function getSlices()
	{
		return $this->hasOne(Slices::className(), ['id' => 'slices_id']);
	}
}
