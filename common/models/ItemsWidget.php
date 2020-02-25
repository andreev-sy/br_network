<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\ItemsFilter;
use common\models\WidgetMain;
use common\models\Slices;
use common\components\QueryFromSlice;
use common\components\ParamsFromQuery;
use common\models\Restaurants;

class ItemsWidget extends Model
{

	public function getMain($filter_model, $slices_model, $main_table){

		$widgets = WidgetMain::find()->with('slice')->all();

	    foreach ($widgets as $key => $widget) {

	    	$slice_obj = new QueryFromSlice($widget->slice->alias);
	    	$params = $this->parseGetQuery($slice_obj->params, $filter_model, $slices_model);

	    	$rooms = new ItemsFilter($params['params_filter'], 7, 1, true, $main_table);

	    	if(count($rooms->items) > 0){
	    		$widget->items = $rooms->items;
	    	}
	    	else{
	    		unset($widgets[$key]);
	    	}
	    }
	    
	    $total = Restaurants::find()->count();

	    //exit;

		return [
			'total' => $total,
			'filter' => false,
			'widgets' => $widgets,
		];
	}

	public function getOther($restaurant_id, $room_id){
		$items = Rooms::find()
			->with('restaurants')
			->where(['restaurant_id' => $restaurant_id])
			->andWhere(['!=', 'id', $room_id])
			->all();

		return $items;
	}

	private function parseGetQuery($getQuery, $filter_model, $slices_model)
	{
		$return = [];
		if(isset($getQuery['page'])){
			$return['page'] = $getQuery['page'];
		}
		else{
			$return['page'] = 1;
		}

		//print_r($getQuery);
		//exit;

		$temp_params = new ParamsFromQuery($getQuery, $filter_model, $slices_model);

		$return['params_api'] = $temp_params->params_api;
		$return['params_filter'] = $temp_params->params_filter;
		$return['listing_url'] = $temp_params->listing_url;
		$return['seo'] = $temp_params->seo;
		return $return;
	}
}