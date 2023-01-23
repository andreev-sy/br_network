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
	public $slices_top;
	public $slices_bot;

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
			[['slices_top', 'slices_bot'], 'safe'],
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

	public function getSliceFilterArray()
	{
		$slice_filter_arr = [];
		$slices = self::find()->all();

		foreach ($slices as $key => $slice) {
			$slice_filter_arr[$slice->type][$slice->id] = $slice->alias;
		}

		return $slice_filter_arr;
	}

	public function getSliceNameById($id)
	{
		// $slice_name = self::find()->where(['id' => $id])->select(['alias', 'h1'])->column();
		$slice_name = self::find()->where(['id' => $id])->select(['alias', 'h1'])->asArray()->all();

		return $slice_name;
	}

	public function afterSave($insert, $changedAttributes)
	{
		// срезы над листингом
		if (!empty($this->slices_top)) {

			foreach (SlicesVia::find()->where(['slice' => $this->id, 'type' => 0])->all() as $item) {
				if (array_search($item->slice_id, $this->slices_top) === false) {
					$item->delete();
				}
			}

			foreach ($this->slices_top as $slice) {
				$sliceVia = new SlicesVia();
				$sliceVia->slice = $this->id;
				$sliceVia->slice_id = $slice;
				$sliceVia->type = 0;
				if (!SlicesVia::find()->where(['slice' => $this->id, 'slice_id' => $slice, 'type' => 0])->exists()) {
					$sliceVia->save();
				}
			}
		} else {
			foreach (SlicesVia::find()->where(['slice' => $this->id, 'type' => 0])->all() as $item) {
				$item->delete();
			}
		}

		// срезы под листингом
		if (!empty($this->slices_bot)) {

			foreach (SlicesVia::find()->where(['slice' => $this->id, 'type' => 1])->all() as $item) {
				if (array_search($item->slice_id, $this->slices_bot) === false) {
					$item->delete();
				}
			}

			foreach ($this->slices_bot as $slice) {
				$sliceVia = new SlicesVia();
				$sliceVia->slice = $this->id;
				$sliceVia->slice_id = $slice;
				$sliceVia->type = 1;
				if (!SlicesVia::find()->where(['slice' => $this->id, 'slice_id' => $slice, 'type' => 1])->exists()) {
					$sliceVia->save();
				}
			}
		} else {
			foreach (SlicesVia::find()->where(['slice' => $this->id, 'type' => 1])->all() as $item) {
				$item->delete();
			}
		}

		parent::afterSave($insert, $changedAttributes);
	}
}