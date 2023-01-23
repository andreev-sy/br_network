<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "slices_via".
 */
class SlicesVia extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'slices_via';
	}


	public function getSliceIds($id, $type)
	{
		$slice_ids = self::find()->where(['slice' => $id, 'type' => $type])->select(['slice_id', 'slice'])->column();

		return $slice_ids;
	}
}
