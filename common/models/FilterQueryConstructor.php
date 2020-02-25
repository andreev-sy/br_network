<?php

namespace common\models;

use yii\base\BaseObject;
use Yii;

class FilterQueryConstructor extends BaseObject{

	public $join = null,
		   $with,
		   $join_iter;

	public function __construct($main_table, $filter_data, $join_iter){

		//Текущая таблица или join
		if($filter_data['table'] == $main_table){
			$table = $filter_data['table'];
		}
		else{
			$this->join = [$filter_data['table'].' as '.$filter_data['table'].'__'.$join_iter];
			$table = $filter_data['table'].'__'.$join_iter;
			//$join_iter++;
		}
		$this->join_iter = $join_iter;

		//Местоположение
		if($filter_data['key'] == 'district'){
			$this->with = [
				'or',
				[$table.'.district' => $filter_data['value']],
				[$table.'.parent_district' => $filter_data['value']]
			];
		}
		//Остальные фильтры
		else{
			//Фильтр со сложными условиями
			if(is_string($filter_data['value']) and in_array(substr($filter_data['value'], 0, 1), ['<','&','>'])){
				switch (substr($filter_data['value'], 0, 1)) {
					case '<':
						$this->with = [
							'<=',
							$table.'.'.$filter_data['key'],
							substr($filter_data['value'], 1)
						];
						break;
					case '&':
						$value_arr = explode(',', substr($filter_data['value'], 1));
						$this->with =  [
							'between',
							$table.'.'.$filter_data['key'],
							$value_arr[0],
							$value_arr[1]
						];
						break;
					case '>':
						$this->with = [
							'>=',
							$table.'.'.$filter_data['key'],
							substr($filter_data['value'],1)
						];
						break;
					default:
						break;
				}
			}
			else{
				$this->with = [$table.'.'.$filter_data['key'] => $filter_data['value']];
			}
		}

	}
}