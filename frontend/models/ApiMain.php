<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\components\RoomsFilter;
use backend\models\Slices;
use frontend\components\QueryFromSlice;
use frontend\components\ParamsFromQuery;
use backend\models\Rooms;

class ApiMain extends Model
{

	public function getMain($widgets, $filter_model, $slices_model){

	    foreach ($widgets as $key => $widget) {

	    	$slice_obj = new QueryFromSlice($widget->slice->alias);
	    	$params = $this->parseGetQuery($slice_obj->params, $filter_model, $slices_model);

	    	$rooms = new RoomsFilter($params['params_filter'], 7, 1, true);

	    	if(count($rooms->items) > 0){
	    		$widget->items = $rooms->items;
	    	}
	    	else{
	    		unset($widgets[$key]);
	    	}
	    }
	    
	    $total = Rooms::find()->count();

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