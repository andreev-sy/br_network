<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;

class AsyncRenewRestaurants extends BaseObject implements \yii\queue\JobInterface
{
	public $gorko_id,
		   $dsn;

	public function execute($queue) {

		if( $curl = curl_init() ) {
		    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/restaurants/'.$this->gorko_id.'?embed=rooms,contacts&fields=address,params,covers,district');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    $response = json_decode(curl_exec($curl), true)['restaurant'];
		    curl_close($curl);

		    $connection = new \yii\db\Connection([
			    'dsn' => $this->dsn,
			    'username' => 'root',
			    'password' => 'LP_db_',
			    'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

		    $model = Restaurants::find()->where(['gorko_id' => $this->gorko_id])->one($connection);
		    
		    if(!$model){
		    	$model = new Restaurants();
		    }

		    $attributes = [];
		    $attributes['gorko_id'] = $response['id'];
		    $attributes['name'] = $response['name'];
			$attributes['address'] = $response['address'];

			$locationStr = $response['params']['param_location'];
			$location = explode(',', $locationStr);
			foreach ($location as $key => $value) {
				if($value != ''){
					$location[$key] = trim($value);
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

			$attributes['img_count'] = 1;
			//$attributes['img_count'] = count($response['covers']);
			//
			//foreach ($response['rooms'] as $key => $room) {
			//	$attributes['img_count'] += count($room['media']);
			//}

			$model->attributes = $attributes;
		    $model->save();

			
			
			foreach ($response['rooms'] as $key => $room) {
				$roomModel = Rooms::find()->where(['gorko_id' => $room['id']])->one($connection);
				$restModel = Restaurants::find()->where(['gorko_id' => $this->gorko_id])->one($connection);
		    
			    if(!$roomModel){
			    	$roomModel = new Rooms();
			    }

			    $roomAttributes = [];
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
			}

			

		    
	  	}
	}
}