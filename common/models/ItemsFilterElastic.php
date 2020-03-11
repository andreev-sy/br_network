<?php

namespace common\models;

use yii\base\BaseObject;
use backend\models\FilterItems;
use backend\models\RestaurantsElastic;
use Yii;

use Elasticsearch\ClientBuilder;

class ItemsFilter extends BaseObject{

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

		$query = ItemsElastic::find();

		$temp_query = [

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

					$filter_query = new FilterQueryConstructor($filter_data);

					if(!isset($temp_query[$filter_query->query_type])){
						$temp_query[$filter_query->query_type] = [];
					}

					foreach ($filter_query->query_arr as $filter_value) {
						array_push($temp_query[$filter_query->query_type], $filter_value);
					}

					$i = $filter_query->join_iter;
				}		
			}			
		}

		$final_query = [
			'bool' => [
				'must' => []
			]
		];

		foreach ($temp_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);

		}
		
		$query->query($final_query);

		$query->limit($limit)
			  ->offset(($offset-1)*$limit);
			  //->addAggregation('restaurant_id', 'terms', [
			  //   'field' => 'restaurant_id',
			  //   'size' => 1,
			  //   //'order' => ['id' => 'desc']
			  //]);



		$elastic_search = $query->search();

		//echo '<pre>';
		//print_r($elastic_search);
		//echo '</pre>';
		//exit;

		if(!$widget_flag){
			$this->total = $elastic_search['hits']['total'];
		}					 

		$this->items = $elastic_search['hits']['hits'];

		$this->pages = ceil($this->total / $limit);
		
	}

}