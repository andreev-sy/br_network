<?php

namespace frontend\components;

use yii\base\BaseObject;
use backend\models\FilterItems;
use backend\models\Restaurants;
use Yii;

class RestaurantsFilter extends BaseObject{

	public $items,
		   $total,
		   $pages,
		   $main_table = 'restaurants';

	public function __construct($filter_arr = [], $limit = 24, $offset = 0, $widget_flag = false) {

		$session = Yii::$app->session;
		if($session->get('seed')){
			$seed = $session->get('seed');
		}
		else{
			$rand_seed = random_int(1, 999999);
			$session->set('seed', $rand_seed);
			$seed = $rand_seed;
		}

		$query = Restaurants::find()->with('rooms');

		$i = 1;
		
		foreach ($filter_arr as $key => $value_temp) {
			$temp_query = ['or'];

			foreach ($value_temp as $value) {
				$filter_item_obj = FilterItems::find()
					->joinWith(['filter'])
					->where(['filter.alias' => $key])
					->andWhere(['value' => $value])
					->one();
				$filter_item_arr = json_decode($filter_item_obj->api_arr, true);
				foreach ($filter_item_arr as $filter_data) {

					$filter_query = new FilterQueryConstructor($this->main_table, $filter_data, $i);
					$i = $filter_query->join_iter;
					if($filter_query->join){
						$query->joinWith([$filter_data['table'].' as '.$filter_data['table'].'__'.$i]);
					}
					$temp_query[] = $filter_query->with;

				}		
			}
			$query->andWhere($temp_query);				
		}		
		
		$total_query = $query;

		$rooms = $query->limit($limit)
							 ->offset(($offset-1)*$limit)
							 ->orderBy('RAND('.$seed.')')
							 ->all();

		$this->items = $rooms;

		if(!$widget_flag){
			$this->total = $total_query->count();
		}		

		$this->pages = ceil($this->total / $limit);

		
	}

}