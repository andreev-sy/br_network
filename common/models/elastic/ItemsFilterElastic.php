<?php

namespace common\models\elastic;

use yii\base\BaseObject;
use backend\models\FilterItems;
use backend\models\RestaurantsElastic;
use Yii;

use Elasticsearch\ClientBuilder;

class ItemsFilterElastic extends BaseObject{

	public $items,
		   $total,
		   $pages;

	public function __construct($filter_arr = [], $limit = 24, $offset = 0, $widget_flag = false, $main_table) {

		$session = Yii::$app->session;
		if($session->get('seed')){
			$seed = $session->get('seed');
		}
		else{
			$rand_seed = random_int(1, 999999);
			$session->set('seed', $rand_seed);
			$seed = $rand_seed;
		}	

		if($main_table == 'rooms'){
			$query = ItemsElastic::find();
		}
		else{
			$query = RestaurantElastic::find();
			$query->source([
				'id',
				'restaurant_cover_url',
				'restaurant_name',
				'restaurant_address',
				'restaurant_min_capacity',
				'restaurant_max_capacity',
			]);
		}		

		$simple_query = [

		];

		$nested_query = [

		];
		
		foreach ($filter_arr as $key => $value_temp) {

			foreach ($value_temp as $value) {
				$filter_item_obj = FilterItems::find()
					->joinWith(['filter'])
					->where(['filter.alias' => $key])
					->andWhere(['value' => $value])
					->one();
				$filter_item_arr = json_decode($filter_item_obj->api_arr, true);

				foreach ($filter_item_arr as $filter_data) {

					$filter_query = new FilterQueryConstructorElastic($filter_data, $main_table);

					if($filter_query->nested){
						if(!isset($nested_query[$filter_query->query_type])){
							$nested_query[$filter_query->query_type] = [];
						}
					}
					else{
						if(!isset($simple_query[$filter_query->query_type])){
							$simple_query[$filter_query->query_type] = [];
						}
					}

					foreach ($filter_query->query_arr as $filter_value) {
						$filter_query->nested ? 
							array_push($nested_query[$filter_query->query_type], $filter_value): 
							array_push($simple_query[$filter_query->query_type], $filter_value);
					}
				}		
			}			
		}

		if($main_table == 'rooms'){
			$final_query = [
				'bool' => [
					'must' => [],
				]
			];
		}
		else{
			$final_query = [
				'bool' => [
					'must' => [
						'nested' => [
							"path" => "rooms",
							"query" => [
								'bool' => [
									'must' => []
								]
							]
						]
					],
				]
			];
		}

			

		foreach ($simple_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
		}

		foreach ($nested_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			if($main_table == 'rooms'){
				array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
			}
			else{
				array_push($final_query["bool"]['must']["nested"]['query']['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
			}
		}

		//echo '<pre>';
		//print_r($final_query);
		//echo '</pre>';
		//exit;

		$final_query = [
			"function_score" => [
		      "query" => $final_query,
		      "functions" => [
	              [
	                  "filter" => [ "match" => [ "restaurant_commission" => "2" ] ],
	                  "random_score" => [], 
	                  "weight" => 100
	              ],
	              [
	              	"random_score" => [ "seed" => $seed ],
	              ]
	          ]
		    ]
		];
		
		$query->query($final_query);

		$query->limit($limit)
			  ->offset(($offset-1)*$limit);

		$elastic_search = $query->search();

		if(!$widget_flag){
			$this->total = $elastic_search['hits']['total'];
		}					 

		$this->items = $elastic_search['hits']['hits'];

		$this->pages = ceil($this->total / $limit);
		
	}

}