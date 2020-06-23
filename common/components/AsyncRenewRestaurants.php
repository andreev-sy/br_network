<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\Images;
use common\components\AsyncRenewImages;

class AsyncRenewRestaurants extends BaseObject implements \yii\queue\JobInterface
{
	public  $gorko_id,
		   	$dsn,
		   	$imageLoad,
		   	$watermark = '/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png';

	public function execute($queue) {

		if( $curl = curl_init() ) {
		    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/restaurants/'.$this->gorko_id.'?embed=rooms,contacts&fields=address,params,covers,district');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    $response = json_decode(curl_exec($curl), true)['restaurant'];
		    curl_close($curl);

		    $connection = new \yii\db\Connection([
			    'dsn' => $this->dsn,
			    'username' => 'pmnetwork',
			    'password' => 'P2t8wdBQbczLNnVT',
			    'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

		    $model = Restaurants::find()->where(['gorko_id' => $this->gorko_id])->one($connection);
		    
		    if(!$model){
		    	$model = new Restaurants();
		    }

		    $attributes = [];
		    $attributes['active'] = 1;
		    $attributes['in_elastic'] = 0;
		    $attributes['gorko_id'] = $response['id'];
		    $attributes['name'] = $response['name'];
			$attributes['address'] = $response['address'];


			$locationStr = $response['params']['param_location']['text'];
			$location = explode(',', $locationStr);
			foreach ($location as $key => $value) {
				if($value != ''){
					$location[$key] = mb_convert_encoding(trim($value), 'UTF-8');
				}
				else{
					unset($location[$key]);
				}
			}
			$location = json_encode($location);
			$attributes['location'] = $location;

			if($response['covers'][0]){
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
			$attributes['own_alcohol'] = isset($response['params']['param_own_alcohol']) ? $response['params']['param_own_alcohol']['text'] : '';
			$attributes['district'] = $response['district']['id'];
			$attributes['parent_district'] = $response['district']['parent_id'] ? $response['district']['parent_id'] : 0;
			$attributes['commission'] = $response['commission'] ? $response['commission'] : 0;
			$attributes['cuisine'] = isset($response['params']['param_cuisine']) ? $response['params']['param_cuisine']['text'] : '';
			if(isset($response['params']['param_firework'])){
				$attributes['firework'] = $response['params']['param_firework']['type'] == 'checked' ? 1 : 0;
			}
			if(isset($response['params']['param_parking'])){
				$attributes['parking'] = $response['params']['param_parking']['type'] == 'checked' ? $response['params']['param_parking']['text'] : '';
			}
			if(isset($response['params']['param_alcohol'])){
				$attributes['alcohol'] = $response['params']['param_alcohol']['type'] == 'checked' ? 1 : 0;
			}
			if(isset($response['params']['param_extra_services'])){
				$attributes['extra_services'] = $response['params']['param_extra_services']['text'] ? $response['params']['param_extra_services']['text'] : '';
			}
			if(isset($response['params']['param_payment'])){
				$attributes['payment'] = $response['params']['param_payment']['text'] ? $response['params']['param_payment']['text'] : '';
			}
			if(isset($response['params']['param_special'])){
				$attributes['special'] = $response['params']['param_special']['text'] ? $response['params']['param_special']['text'] : '';
			}

			foreach ($response['contacts'] as $key => $value) {
				if($value['key'] == 'phone'){
					$attributes['phone'] = $value['value'];
				}
			}

			$model->attributes = $attributes;
		    $model->save();

		    $restModel = Restaurants::find()->where(['gorko_id' => $response['id']])->one($connection);

		    if($restModel){

			    foreach ($response['covers'] as $key => $image) {
					$imgModel = Images::find()->where(['gorko_id' => $image['id']])->one($connection);
					$imgAttributes = [];
			    
				    if(!$imgModel){
				    	$imgModel = new Images();
				    	$imgAttributes['gorko_id'] = $image['id'];
				    	$imgAttributes['sort'] = $key;
				    	$imgAttributes['realpath'] = str_replace('=s0', '', $image['original_url']);
				    	$imgAttributes['type'] = 'restaurant';
				    	$imgAttributes['item_id'] = $restModel->id;
				    	$imgModel->attributes = $imgAttributes;
				    	$imgModel->save();

				    	$queue_id = Yii::$app->queue->push(new AsyncRenewImages([
							'item_id' => $restModel->id,
							'dsn' => $this->dsn,
							'type' => 'restaurant',
							'watermark' => $this->watermark
						]));
				    }			    
				}
				
				foreach ($response['rooms'] as $key => $room) {
					$roomModel = Rooms::find()->where(['gorko_id' => $room['id']])->one($connection);
			    
				    if(!$roomModel){
				    	$roomModel = new Rooms();
				    }

				    $roomAttributes = [];
				    $roomAttributes['active'] = 1;
		    		$roomAttributes['in_elastic'] = 0;
			    	$roomAttributes['gorko_id'] = $room['id'];
			    	$roomAttributes['name'] = $room['name'];
			    	$roomAttributes['restaurant_id'] = $restModel->id;
			    	$roomAttributes['price'] = $room['prices'][0]['value'];
			    	$roomAttributes['capacity'] = $room['params']['param_capacity']['value'];

			    	if($room['cover_url']){
						$roomAttributes['cover_url'] = str_replace("w230-h150-n-l95", "w445-h302-n-l95", $room['cover_url']);
					}

			    	if(isset($room['params']['param_capacity_reception'])){
			    		$roomAttributes['capacity_reception'] = $room['params']['param_capacity_reception']['value'] ? $room['params']['param_capacity_reception']['value'] : 0;
			    	}
			    	
			    	$roomAttributes['type'] = $room['type'];
			    	$roomAttributes['type_name'] = $room['type_name'];

			    	if(isset($room['params']['param_rent_only'])){
						$roomAttributes['rent_only'] = $room['params']['param_rent_only']['value'] == 1 ? 1 : 0;
					}
					if(isset($room['params']['param_banquet_price'])){
						$roomAttributes['banquet_price'] = $room['params']['param_banquet_price']['value'] ? $room['params']['param_banquet_price']['value'] : 0;
					}
					if(isset($room['params']['param_bright_room'])){
						$roomAttributes['bright_room'] = $room['params']['param_bright_room']['value'] == 1 ? 1 : 0;
					}
					if(isset($room['params']['param_separate_entrance'])){
						$roomAttributes['separate_entrance'] = $room['params']['param_separate_entrance']['value'] == 1 ? 1 : 0;
					}
					if(isset($room['params']['param_features'])){
						$roomAttributes['features'] = $room['params']['param_features']['value'] ? $room['params']['param_features']['value'] : '';
					}

			    	$roomModel->attributes = $roomAttributes;
			    	$roomModel->save();

			    	$roomModel = Rooms::find()->where(['gorko_id' => $room['id']])->one($connection);

			    	if($roomModel){
			    		foreach ($room['media'] as $key => $image) {
							$imgModel = Images::find()->where(['gorko_id' => $image['id']])->one($connection);
							$imgAttributes = [];
					    
						    if(!$imgModel){
						    	$imgModel = new Images();
						    	$imgAttributes['gorko_id'] = $image['id'];
						    	$imgAttributes['sort'] = $key;
						    	$imgAttributes['realpath'] = str_replace('=s0', '', $image['original_url']);
						    	$imgAttributes['type'] = 'rooms';
						    	$imgAttributes['item_id'] = $roomModel->id;
						    	$imgModel->attributes = $imgAttributes;
					    		$imgModel->save();

					    		$queue_id = Yii::$app->queue->push(new AsyncRenewImages([
									'item_id' => $roomModel->id,
									'dsn' => $this->dsn,
									'type' => 'rooms',
									'watermark' => $this->watermark
								]));
						    }				    
						}
			    	}		    	
				}
			}	    
	  	}
	}
}