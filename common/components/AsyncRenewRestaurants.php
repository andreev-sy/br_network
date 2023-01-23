<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\RoomsSpec;
use common\models\Images;
use common\components\AsyncRenewImages;
use common\models\elastic\ApiLoaderLogElastic;

class AsyncRenewRestaurants extends BaseObject implements \yii\queue\JobInterface
{
	public  $gorko_id,
		   	$connection_config;

	public function execute($queue) {
		$status = 'ok';
		try{
			if( $curl = curl_init() ) {
			    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/restaurants/'.$this->gorko_id.'?embed=rooms,contacts&fields=address,params,covers,district,metro,specs,room_specs&is_edit=1');
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			    curl_setopt($curl, CURLOPT_ENCODING, '');
			    $response = json_decode(curl_exec($curl), true)['restaurant'];
			    curl_close($curl);

			    $connection = new \yii\db\Connection($this->connection_config);
				$connection->open();
				Yii::$app->set('db', $connection);

			    $model = Restaurants::find()->where(['gorko_id' => $this->gorko_id])->one();
			    
			    if(!$model){
			    	$model = new Restaurants();
			    }

			    $attributes = [];
			    $attributes['active'] = 1;
			    $attributes['in_elastic'] = 0;
			    $attributes['gorko_id'] = $response['id'];
			    $attributes['name'] = strval($response['name']);
				$attributes['address'] = $response['address'];
				print_r($response['id']);

				if(isset($response['params']['param_location'])){
					$attributes['location'] = '';
					$flag = true;
					foreach ($response['params']['param_location']['value'] as $location){
						if($flag){
							$attributes['location'] .= $location;
							$flag = false;
						}
						else{
							$attributes['location'] .= ','.$location;
						}
						
					}
				}

				if(isset($response['params']['param_type'])){
					$attributes['type'] = '';
					$flag = true;
					foreach ($response['params']['param_type']['value'] as $type){
						if($flag){
							$attributes['type'] .= $type;
							$flag = false;
						}
						else{
							$attributes['type'] .= ','.$type;
						}
						
					}
				}

						

				if(isset($response['covers'][0])){
					$attributes['cover_url'] = str_replace("=s0", "", $response['covers'][0]['original_url']);
				}

				if(count($response['capacity']) > 0){
					$min_capacity = min($response['capacity']);
					$max_capacity = max($response['capacity']);
					$attributes['min_capacity'] = $min_capacity;
					$attributes['max_capacity'] = $max_capacity;
				}

				$attributes['latitude'] = strval($response['latitude']);
				$attributes['longitude'] = strval($response['longitude']);
				$attributes['district'] = isset($response['district']['id']) ? $response['district']['id'] : 0;
				$attributes['parent_district'] = isset($response['district']['parent_id']) ? $response['district']['parent_id'] : 0;
				$attributes['city_id'] = $response['city']['id'];
				$attributes['commission'] = $response['commission'] ? $response['commission'] : 0;
				$attributes['top'] = $response['top'] ? $response['top'] : 0;
				if(isset($response['params']['param_own_alcohol']) && isset($response['params']['param_own_alcohol']['display']['text'])){
					$attributes['own_alcohol'] = $response['params']['param_own_alcohol']['display']['text'] ? $response['params']['param_own_alcohol']['display']['text'] : '';
				}

				

				if(isset($response['params']['param_own_alcohol'])){
					$attributes['alcohol'] = $response['params']['param_own_alcohol']['value'];
				}
				$attributes['alcohol_stock'] = isset($response['params']['param_alcohol']) ? $response['params']['param_alcohol']['value'] : '';
				if(isset($response['params']['param_cuisine']) && isset($response['params']['param_cuisine']['display']['text'])){
					$attributes['cuisine'] = $response['params']['param_cuisine']['display']['text'] ? $response['params']['param_cuisine']['display']['text'] : '';
				}
				print_r('-1-');
				if(isset($response['params']['param_firework'])){
					$attributes['firework'] = $response['params']['param_firework']['value'];
				}
				if(isset($response['params']['param_parking'])){
					$attributes['parking'] = $response['params']['param_parking']['value'];
				}
				if(isset($response['params']['param_extra_services']) && isset($response['params']['param_extra_services']['display']['text'])){
					$attributes['extra_services'] = $response['params']['param_extra_services']['display']['text'] ? $response['params']['param_extra_services']['display']['text'] : '';
				}
				print_r('-2-');
				if(isset($response['params']['param_payment']) && isset($response['params']['param_payment']['display']['text'])){
					$attributes['payment'] = $response['params']['param_payment']['display']['text'] ? $response['params']['param_payment']['display']['text'] : '';
				}
				if(isset($response['params']['param_special']) && isset($response['params']['param_special']['display']['text'])){
					$attributes['special'] = $response['params']['param_special']['display']['text'] ? $response['params']['param_special']['display']['text'] : '';
				}	

				

				//$attributes['metro_station_id'] = count($response['metro']) > 0? $response['metro'][0]['id'] : 0;
				//ТИП ПРАЗДНИКА
				$attributes['restaurants_spec'] = '';
				$flag = true;
				foreach ($response['room_specs'] as $spec) {
					if ($flag) {
						$attributes['restaurants_spec'] .= $spec['id'];
						$flag = false;
					} else {
						$attributes['restaurants_spec'] .= ',' . $spec['id'];
					}
				}

				//Сервисы за отдельную плату
				$attributes['extra_services_ids'] = '';
				$flag = true;
				foreach ($response['params']['param_extra_services']['value'] as $key => $value) {
					if ($flag) {
						$attributes['extra_services_ids'] .= $value;
						$flag = false;
					} else {
						$attributes['extra_services_ids'] .= ',' . $value;
					}
				}

				//Особенности
				$attributes['special_ids'] = '';
				$flag = true;
				foreach ($response['params']['param_special']['value'] as $key => $value) {
					if ($flag) {
						$attributes['special_ids'] .= $value;
						$flag = false;
					} else {
						$attributes['special_ids'] .= ',' . $value;
					}
				}

				foreach ($response['contacts'] as $key => $value) {
					if($value['key'] == 'phone'){
						$attributes['phone'] = $value['value'];
					}
				}

				if(isset($response['schema']['aggregateRating']) && isset($response['schema']['aggregateRating']['ratingValue'])){
					$rating = intval($response['schema']['aggregateRating']['ratingValue']*10);
					$attributes['rating'] = $rating ? $rating : '';
				}

				$model->attributes = $attributes;
				$model->validate();

				

				if ($model->save()) {
					$status = 'ok';
				}else{
					$status = [
						$attributes['gorko_id'] => [
							'attributes' => $model->getAttributes(),
							'errors' => $model->getErrors(),
						]
					];
				}
			    $rest_save = $model->save();		    

			    $restModel = Restaurants::find()->where(['gorko_id' => $response['id']])->one();

			    if($restModel){

				    //foreach ($response['covers'] as $key => $image) {
					//	$imgModel = Images::find()->where(['gorko_id' => $image['id']])->one();
					//	$imgAttributes = [];
//
					//	if($imgModel && ($imgModel->item_id != $restModel->id)) {
					//		$imgModel->item_id = $restModel->gorko_id;
					//		$imgModel->save();
					//	}
				    //
					//    if(!$imgModel){
					//    	$imgModel = new Images();
					//    	$imgAttributes['gorko_id'] = $image['id'];
					//    	$imgAttributes['sort'] = $key;
					//    	$imgAttributes['realpath'] = str_replace('=s0', '', $image['original_url']);
					//    	$imgAttributes['type'] = 'restaurant';
					//    	$imgAttributes['item_id'] = $restModel->gorko_id;
					//    	$imgAttributes['timestamp'] = time();
					//    	$imgModel->attributes = $imgAttributes;
					//    	$imgModel->save();				    	
					//    }
					//}
					
					foreach ($response['rooms'] as $key => $room) {
						$roomModel = Rooms::find()->where(['gorko_id' => $room['id']])->one();
				    
					    if(!$roomModel){
					    	$roomModel = new Rooms();
					    }

					    $roomAttributes = [];
					    $roomAttributes['active'] = 1;
			    		$roomAttributes['in_elastic'] = 0;
				    	$roomAttributes['gorko_id'] = $room['id'];
				    	$roomAttributes['name'] = $room['name'] ? $room['name'] : $room['type_name'];
				    	$roomAttributes['restaurant_id'] = $restModel->gorko_id;
				    	if(isset($room['prices'][0])){
				    		$roomAttributes['price'] = $room['prices'][0]['value'];
				    	}				    	
				    	$roomAttributes['capacity'] = $room['params']['param_capacity_0']['value'];

				    	if($room['cover_url']){
							$roomAttributes['cover_url'] = str_replace("w230-h150-n-l95", "w445-h302-n-l95", $room['cover_url']);
						}

				    	if(isset($room['params']['param_capacity_reception_0'])){
				    		$roomAttributes['capacity_reception'] = $room['params']['param_capacity_reception_0']['value'] ? $room['params']['param_capacity_reception_0']['value'] : 0;
				    	}
				    	
				    	$roomAttributes['type'] = $room['type'];
				    	$roomAttributes['type_name'] = $room['type_name'];

				    	if(isset($room['params']['param_rent_only_0'])){
							$roomAttributes['rent_only'] = $room['params']['param_rent_only_0']['value'] == 1 ? 1 : 0;
						}
						if(isset($room['params']['param_bright_room_0'])){
							$roomAttributes['bright_room'] = $room['params']['param_bright_room_0']['value'] == 1 ? 1 : 0;
						}
						if(isset($room['params']['param_separate_entrance_0'])){
							$roomAttributes['separate_entrance'] = $room['params']['param_separate_entrance_0']['value'] == 1 ? 1 : 0;
						}
						if(isset($room['params']['param_features_0'])){
							$roomAttributes['features'] = $room['params']['param_features_0']['value'] ? $room['params']['param_features_0']['value'] : '';
						}

						if(isset($room['params']['param_min_price_0']) && $room['params']['param_min_price_0']['value']){
							$roomAttributes['banquet_price'] = $room['params']['param_min_price_0']['value'];
						}
						else{
							if(isset($room['prices'][0])){
								$roomAttributes['banquet_price'] = $room['prices'][0]['value'] * $room['params']['param_capacity_0']['value'];
							}
						}

						if(isset($room['params']['param_graduation_category_11']) && is_array($room['params']['param_graduation_category_11']['value'])){
							$roomAttributes['graduation_category'] = '';
							$flag = true;
							foreach ($room['params']['param_graduation_category_11']['value'] as $graduation_category){
								if($flag){
									$roomAttributes['graduation_category'] .= $graduation_category;
									$flag = false;
								}
								else{
									$roomAttributes['graduation_category'] .= ','.$graduation_category;
								}	
							}
						}

						if(isset($room['params']['param_payment_model_0'])){
							$roomAttributes['payment_model'] = $room['params']['param_payment_model_0']['value'] ? $room['params']['param_payment_model_0']['value'] : 0;
						}

				    	$roomModel->attributes = $roomAttributes;
				    	$roomModel->save();			    	

				    	$roomModel = Rooms::find()->where(['gorko_id' => $room['id']])->one();

				    	$arr_spec_prices = array();
				    	for ($i=1; $i<85; $i++) {
					    	if (isset($room['params']['param_banquet_price_'.$i])) {
					    		$spec_price = $room['params']['param_banquet_price_'.$i];
					    		if ($spec_price['value'] > 0) {
					    			$arr_spec_prices[$spec_price['spec_id']] = $spec_price['value'];
					    		}
					    	}
					    }

				    	if (!empty($arr_spec_prices)){
				    		RoomsSpec::updateSpecPrices($roomModel->id, $roomModel->gorko_id, $arr_spec_prices);
				    	}

				    	//if($roomModel){
				    	//	foreach ($room['media'] as $key => $image) {
						//		$imgModel = Images::find()->where(['gorko_id' => $image['id']])->one();
						//		$imgAttributes = [];
//
						//		if($imgModel && ($imgModel->item_id != $roomModel->id)) {
						//			$imgModel->item_id = $roomModel->gorko_id;
						//			$imgModel->save();
						//		}
						//    
						//	    if(!$imgModel){
						//	    	$imgModel = new Images();
						//	    	$imgAttributes['gorko_id'] = $image['id'];
						//	    	$imgAttributes['sort'] = $key;
						//	    	$imgAttributes['realpath'] = str_replace('=s0', '', $image['original_url']);
						//	    	$imgAttributes['type'] = 'rooms';
						//	    	$imgAttributes['item_id'] = $roomModel->gorko_id;
						//	    	$imgAttributes['timestamp'] = time();
						//	    	$imgModel->attributes = $imgAttributes;
						//    		$imgModel->save();
						//	    }			    
						//	}
				    	//}    	
					}
				}	    
		  	}
		} catch (Exception $e) {
		    //$status = 'Выброшено исключение: '.  $e->getMessage() . "\n";
		}

		$log_arr = [
			'rest_id' => $this->gorko_id,
			'status' => json_encode($status),
			'date' => date("Y-m-d H:i:s", time() + (4 * 60 * 60)),
		];

		$apiLog = new ApiLoaderLogElastic();
        $apiLog::addRecord($log_arr);


	  	return 1;
	}
}