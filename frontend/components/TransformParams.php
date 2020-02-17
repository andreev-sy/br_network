<?php
namespace frontend\components;

use Yii;

class TransformParams {
	public static function restaurant($restaurant) {
		//ВМЕСТИМОСТЬ
		if(count($restaurant['capacity']) > 0){
			$min_capacity = min($restaurant['capacity']);
			$max_capacity = max($restaurant['capacity']);
			if($min_capacity != $max_capacity){
				$temp_arr['capacity'] = [$min_capacity, $max_capacity];
			}
			else{
				$temp_arr['capacity'] = $max_capacity;
			}
		}
		else{
			$temp_arr['capacity'] = false;
		}

		//АЛКОГОЛЬ
		//if(in_array($restaurant['params']['param_own_alcohol']['value'], [1,2])){
		//	$temp_arr['alco'] = $restaurant['params']['param_own_alcohol']['display']['text'];
		//}
		//else{
			$temp_arr['alco'] = false;
		//}

		//СТОИМОСТЬ
		$temp_price = [];
		foreach ($restaurant['rooms'] as $room) {
			isset($room['prices'][0]['value']) ? $temp_price[] = $room['prices'][0]['value'] : false;
		}
		count($temp_price) > 0 ? $temp_arr['price'] = min($temp_price) : $temp_arr['price'] = false;
		return $temp_arr;
	}
}