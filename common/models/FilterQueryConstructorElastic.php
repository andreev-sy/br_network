<?php

namespace common\models;

use yii\base\BaseObject;
use Yii;

class FilterQueryConstructor extends BaseObject{

	public $join = null,
		   $query_arr,
		   $query_type,
		   $join_iter;

	public function __construct($filter_data){

		$prefix = '';

		if($filter_data['table'] == 'restaurants'){
			$prefix = 'restaurant_';
		}

		$this->query_type = $filter_data['key'];
		$this->query_arr = [];

		//Местоположение
		if($filter_data['key'] == 'district'){
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
}