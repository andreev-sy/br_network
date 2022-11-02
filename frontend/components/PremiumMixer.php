<?php

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use common\models\elastic\ItemsFilterElastic;

class PremiumMixer extends BaseObject{
	public static function getItemsWithPremium($filter_arr = [], $limit = 24, $offset = 0, $widget_flag = false, $main_table, $elastic_model = false, $random = false, $must_not = false, $api_subdomen = false, $console = false, $price_sort = false, $rating_sort = false){
		$premium_items = new ItemsFilterElastic($filter_arr, $limit, 0, $widget_flag, $main_table, $elastic_model, $random, $must_not, $api_subdomen, $console, false, false, true);
		if(count($premium_items->items)) $limit = $limit - count($premium_items->items);
		$items = new ItemsFilterElastic($filter_arr, $limit, $offset, $widget_flag, $main_table, $elastic_model, $random, $must_not, $api_subdomen, $console, $price_sort, $rating_sort);

		if($offset == 1){
			if($premium_items->total)
				$items->items = array_merge($premium_items->items, $items->items);
		}
		else{
			foreach ($premium_items->items as $key => $premium_item) {
				array_splice($items->items, rand(0, count($items->items)), 0, [$key => $premium_item]);
			}			
		}

		return $items;
	}
}