<?php

namespace frontend\components;

use yii\base\BaseObject;
use backend\models\FilterItems;
use backend\models\Rooms;
use Yii;

class RoomsFilter extends BaseObject{

	public $items,
		   $total,
		   $pages;

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

		$rooms_query = Rooms::find()->with('restaurants');

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
					if($filter_data['table'] != 'rooms'){
						$rooms_query->joinWith([$filter_data['table'].' as '.$filter_data['table'].'__'.$i]);
						$temp_query[] = ['or', [$filter_data['table'].'__'.$i.'.district' => $filter_data['value']], [$filter_data['table'].'__'.$i.'.parent_district' => $filter_data['value']]];
						$i++;
					}
					else{
						if(is_string($filter_data['value']) and in_array(substr($filter_data['value'], 0, 1), ['<','&','>'])){
							switch (substr($filter_data['value'], 0, 1)) {
								case '<':
									$temp_query[] = [
										'<=',
										$filter_data['table'].'.'.$filter_data['key'],
										substr($filter_data['value'], 1)
									];
									break;
								case '&':
									$value_arr = explode(',', substr($filter_data['value'], 1));
									$temp_query[] =  [
										'between',
										$filter_data['table'].'.'.$filter_data['key'],
										$value_arr[0],
										$value_arr[1]
									];
									break;
								case '>':
									$temp_query[] = [
										'>=',
										$filter_data['table'].'.'.$filter_data['key'],
										substr($filter_data['value'],1)
									];
									break;
								default:
									break;
							}
						}
						else{
							$temp_query[] = [$filter_data['table'].'.'.$filter_data['key'] => $filter_data['value']];
						}
						
					}
				}		
			}
			$rooms_query->andWhere($temp_query);				
		}

		//echo '<pre>';
    	//print_r($rooms_query);
    	//echo '</pre>';
		//exit;

		
		
		$total_query = $rooms_query;

		$rooms = $rooms_query->limit($limit)
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