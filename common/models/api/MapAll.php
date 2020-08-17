<?php

namespace common\models\api;

use yii\base\BaseObject;
use common\models\Restaurants;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\elastic\ItemsFilterElastic;

class MapAll extends BaseObject{

	public $coords;

	public function __construct($elastic_model, $subdomain_id) {

		$restaurants = new ItemsFilterElastic([], 1000, 1, false, 'restaurants', $elastic_model, false, false, $subdomain_id);
		$this->coords = [
			'type' => 'FeatureCollection',
			'features' => []
		];

		foreach ($restaurants->items as $key => $restaurant) {
			array_push($this->coords['features'], [
				'type' => "Feature",
	            'id' => $restaurant->id,
	            'geometry' => [
	              'type' => "Point",
	              'coordinates' => [$restaurant->restaurant_latitude, $restaurant->restaurant_longitude]
	            ],
	            'properties' => [
	              'balloonContent' => $restaurant->restaurant_address,
	              'organization' => $restaurant->restaurant_name,
	              'address' => $restaurant->restaurant_address,
	              //'img' => $restaurant->restaurant_images[0]->subpath,
	              'clusterCaption' => $restaurant->restaurant_name
	            ]
			]);
		}		
	}

}