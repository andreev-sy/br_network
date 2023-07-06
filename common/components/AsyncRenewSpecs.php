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

class AsyncRenewSpecs extends BaseObject implements \yii\queue\JobInterface
{
	public  $gorko_id,
			$restaurant,
		   	$connection_config;
	
	public function execute($queue) {
		$this->renew_specs($this->gorko_id, $this->restaurant, $this->connection_config);
		return 1;
	}

	public function premium_rest() {
		$this->renew_specs($this->gorko_id, $this->restaurant, $this->connection_config);
		return 1;
	}

	private function renew_specs($gorko_id, $restaurant, $connection_config) {
		$connection = new \yii\db\Connection($connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);

		$arr_rooms  = $arr_gorko_rooms = $arr_spec_prices = array();
		foreach ($restaurant->rooms as $room) {
			array_push($arr_rooms, $room->gorko_id);
			$arr_gorko_rooms[$room->gorko_id] = $room->id; 
		}
		$arr_room_specs = RoomsSpec::getSpecsForRest($arr_rooms);

		try{
			if( $curl = curl_init() ) {
			    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v3/venue/'. $gorko_id.'?entity[channel]=a&entity[languageId]=1');
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			    curl_setopt($curl, CURLOPT_ENCODING, '');
			    $response = json_decode(curl_exec($curl), true);
			    if (!isset($response['entity'][$gorko_id])) return 1;
		    	$response = $response['entity'][$gorko_id];
			    curl_close($curl);
			    $arr_gorko_room_specs = array();

			    foreach ($response['room'] as $room) {
			    	$arr_gorko_room_specs[$room['id']] = array();
			    	foreach ($room['spec'] as $spec) {
		    			array_push($arr_gorko_room_specs[$room['id']], $spec['id']);
			    	}
			    }
			}
		} catch (Exception $e) {
		    //echo 'Выброшено исключение: '.  $e->getMessage() . "\n";
		}
		
		foreach ($arr_gorko_room_specs as $gorko_id => $spec_ids) {
			$arr_spec_prices = array();
			foreach ($spec_ids as $spec_id) {
				if (!isset($arr_room_specs[$gorko_id]) || !in_array($spec_id, array_keys($arr_room_specs[$gorko_id]))) {
					$arr_spec_prices[$spec_id] = NULL;
				} else {
					$arr_spec_prices[$spec_id] = $arr_room_specs[$gorko_id][$spec_id];
				}
			}
			
			RoomsSpec::updateSpecPrices($arr_gorko_rooms[$gorko_id], $gorko_id, $arr_spec_prices);
		}

	  	return 1;
	}
}