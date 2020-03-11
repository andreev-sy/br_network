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
		$this->coords = [
			'type' => 'FeatureCollection',
			'features' => []
		];

		foreach ($restaurants as $key => $restaurant) {
			array_push($this->coords['features'], [
				'type' => "Feature",
	            'id' => $restaurant->id,
	            'geometry' => [
	              'type' => "Point",
	              'coordinates' => [$restaurant->latitude, $restaurant->longitude]
	            ],
	            'properties' => [
	              'balloonContent' => $restaurant->address,
	              'organization' => $restaurant->name,
	              'address' => $restaurant->address,
	              'img' => $restaurant->cover_url,
	              'clusterCaption' => $restaurant->name
	            ]
			]);
		}		
	}

}