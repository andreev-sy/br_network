<?php

namespace common\models\api;

use yii\base\BaseObject;
use common\models\Restaurants;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\elastic\ItemsFilterElastic;

class MapAll extends BaseObject{

	public $coords;

	public function __construct($elastic_model, $subdomain_id, $filter = []) {

		$restaurants = new ItemsFilterElastic($filter, 9000, 1, false, 'restaurants', $elastic_model, false, false, $subdomain_id);
		$this->coords = [
			'type' => 'FeatureCollection',
			'features' => [],
			'filter' => $filter
		];

		foreach ($restaurants->items as $key => $restaurant) {
			foreach ($restaurant->restaurant_images as $key => $image) {
				$map_preview = $image['subpath'];
				break;
			}

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
	              'img' => $map_preview,
	              'clusterCaption' => $restaurant->restaurant_name,
	              'link' => '/ploshhadki/'.$restaurant->id.'/',
	            ]
			]);
		}		
	}

}