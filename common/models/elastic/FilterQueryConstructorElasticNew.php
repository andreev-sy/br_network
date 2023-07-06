<?php

namespace common\models\elastic;

use yii\base\BaseObject;
use Yii;

class FilterQueryConstructorElasticNew extends BaseObject{

	public $join = null,
		   $query_arr,
		   $query_type,
		   $nested,
		   $type,
		   $location,
		   $metro,
		   $spec,
		   $specials,
		   $extra,
		   $rooms_spec;

	public function __construct($filter_data, $main_table){

		$prefix = '';

		if($main_table == 'rooms'){
			if($filter_data['table'] == 'restaurants'){
				$prefix = 'restaurant_';
			}
		}
		else{
			if($filter_data['table'] == 'restaurants'){
				$prefix = 'restaurant_';
			}
			else{
				$prefix = 'rooms.';
			}
		}

		
		$this->query_type = $filter_data['key'];
		$this->query_arr = [];
		$this->nested = ($filter_data['table'] == 'rooms' and $filter_data['table'] != $main_table);
		$this->type = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'types.id');
		$this->spec = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'spec.id');
		$this->specials = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'specials.id');
		$this->extra = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'extra.id');
		$this->location = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'location.id');
		$this->metro = ($filter_data['table'] == 'restaurants' and $filter_data['key'] == 'metro_stations.id');
		$this->rooms_spec = ($filter_data['table'] == 'rooms' and $filter_data['key'] == 'room_spec.id');

		//print_r($filter_data['key']);

		//Локация зала
		if($filter_data['key'] == 'location'){
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['term' => [$prefix.$this->getLocationCode($value) => 1]]);
				}
			}
			else{
				$this->query_arr = [
					['term' => [$prefix.$this->getLocationCode($value) => 1]]
				];
			}
		}
		//Тип
		if($filter_data['key'] == 'spec.id'){
			$this->query_arr = [
				['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
			];
		}

		//Тип по залу
		if($filter_data['key'] == 'room_spec.id'){
			$this->query_arr = [
				['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
			];
		}

		//Тип мероприятия
		if($filter_data['key'] == 'types.id'){
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['match' => [$prefix.$filter_data['key'] => $value]]);
				}
			}
			else{
				$this->query_arr = [
					["match" => [
						$prefix.$filter_data['key'] => $filter_data['value']
					]]
				];
			}
		}

		//Особенности
		if($filter_data['key'] == 'specials.id'){
			// $this->query_arr = [
			// 	['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
			// ];
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['match' => [$prefix.$filter_data['key'] => $value]]);
				}
			}
			else{
				$this->query_arr = [
					["match" => [
						$prefix.$filter_data['key'] => $filter_data['value']
					]]
				];
			}
		}

		//Дополнительные пар-тры
		if($filter_data['key'] == 'extra.id'){
			$this->query_arr = [
				['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
			];
		}

		//Метро ресторана
		if($filter_data['key'] == 'metro_stations.id'){
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['match' => [$prefix.$filter_data['key'] => $value]]);
				}
			}
			else{
				$this->query_arr = [
					["match" => [
						$prefix.$filter_data['key'] => $filter_data['value']
					]]
				];
			}
		}
		
		//Локация ресторана
		if($filter_data['key'] == 'location.id'){
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['match' => [$prefix.$filter_data['key'] => $value]]);
				}
			}
			else{
				$this->query_arr = [
					["match" => [
						$prefix.$filter_data['key'] => $filter_data['value']
					]]
				];
			}
		}


		//Местоположение
		elseif($filter_data['key'] == 'district'){
			if(is_array($filter_data['value'])){
				foreach ($filter_data['value'] as $key => $value) {
					array_push($this->query_arr, ['term' => [$prefix."district" => $value]]);
					array_push($this->query_arr, ['term' => [$prefix."parent_district" => $value]]);
				}
			}
			else{
				$this->query_arr = [
					['term' => [$prefix."district" => $filter_data['value']]],
					['term' => [$prefix."parent_district" => $filter_data['value']]]
				];
			}
		}
		//Местоположение
		elseif($filter_data['key'] == 'capacity'){
			switch (substr($filter_data['value'], 0, 1)) {
				case '<':
					$this->query_arr = [
						[
							"range" => [
								$prefix.'capacity_min' => [
									'lte' => str_replace('<', '', $filter_data['value'])
								]
							]
						]
					];
					break;
				case '&':
					$value_arr = explode(',', substr($filter_data['value'], 1));
					$this->query_arr = [
						'capacity' => [
							'bool' => [
								'must' => [],
							]
						]
					];
					array_push($this->query_arr['capacity']['bool']['must'], [
						"range" => [
							$prefix.$filter_data['key'] => [
								'gte' => $value_arr[0]
							]
						]
					]);
					array_push($this->query_arr['capacity']['bool']['must'], [
						"range" => [
							$prefix.'capacity_min' => [
								'lte' => $value_arr[1]
							]
						]
					]);
					break;
				case '>':
					$this->query_arr = [
						[
							"range" => [
								$prefix.$filter_data['key'] => [
									'gte' => str_replace('>', '', $filter_data['value'])
								]
							]
						]
					];
					break;
				default:
					break;
			}
		}
		//Остальные фильтры
		else{
			//Фильтр со сложными условиями
			if(is_string($filter_data['value']) and in_array(substr($filter_data['value'], 0, 1), ['<','&','>'])){
				switch (substr($filter_data['value'], 0, 1)) {
					case '<':
						$this->query_arr = [
							[
								"range" => [
									$prefix.$filter_data['key'] => [
										'lte' => str_replace('<', '', $filter_data['value'])
									]
								]
							]
						];
						break;
					case '&':
						$value_arr = explode(',', substr($filter_data['value'], 1));
						$this->query_arr = [
							[
								"range" => [
									$prefix.$filter_data['key'] => [
										'gte' => $value_arr[0],
										'lte' => $value_arr[1]
									]
								]
							]
						];
						break;
					case '>':
						$this->query_arr = [
							[
								"range" => [
									$prefix.$filter_data['key'] => [
										'gte' => str_replace('>', '', $filter_data['value'])
									]
								]
							]
						];
						break;
					default:
						break;
				}
			}
			else{
				if(is_array($filter_data['value'])){
					foreach ($filter_data['value'] as $key => $value) {
						array_push($this->query_arr, ['term' => [$prefix.$filter_data['key'] => $value]]);
					}
				}
				else{
					$this->query_arr = [
						["term" => [
							$prefix.$filter_data['key'] => $filter_data['value']
						]]
					];
				}
					
			}
		}
	}

	public function getLocationCode($value){
		$return = '';
		switch ($value) {
			case 'Около моря':
				$return = 'location_sea';
				break;
			case 'Около реки':
				$return = 'location_river';
				break;
			case 'Около озера':
				$return = 'location_lake';
				break;
			case 'В горах':
				$return = 'location_mount';
				break;
			case 'В городе':
				$return = 'location_city';
				break;
			case 'В центре города':
				$return = 'location_center';
				break;
			case 'За городом':
				$return = 'location_outside';
				break;

		}
		return $return;
	}
}