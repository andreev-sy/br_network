<?php

namespace common\models\api;

use yii\base\BaseObject;
use common\models\Restaurants;
use yii\helpers\ArrayHelper;
use Yii;

class MapAll extends BaseObject{

	public $coords;

	public function __construct() {

		$restaurants = Restaurants::find()->all();
		$this->coords = ArrayHelper::toArray($restaurants, [
		    'common\models\Restaurants' => [
		        'id',
		        'name',
		        'address',
		        'latitude',
		        'longitude'
		    ],
		]);
		
	}

}