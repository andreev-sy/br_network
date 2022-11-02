<?php

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use backend\models\Slices;

class QueryFromSlice extends BaseObject{

	public $params;
	public $seo;
	public $flag = false;
	public $slice_model = null;

	public function __construct($slice) {
		$this->slice_model = $slice_model = Slices::find()->where(['alias' => $slice])->one();
		if($slice_model){
			$this->params = json_decode($slice_model->params, true);
			$this->seo = [
				'h1' => $slice_model->h1,
	            'title' => $slice_model->title,
	            'description' => $slice_model->description,
	            'keywords' => $slice_model->keywords,
	            'text_top' => $slice_model->text_top,
	            'text_bottom' => $slice_model->text_bottom,
	            'img_alt' => $slice_model->img_alt,
					'feature' => $slice_model->feature,
			];
			$this->flag = true;
		}
	}
}