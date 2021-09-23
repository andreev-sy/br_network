<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\Subdomen;
use common\components\AsyncRenewRestaurants;
use common\components\AsyncRenewImagesExt;

class GorkoApi extends Model
{
	public function renewAllData($connection_config) {
		$connection = new \yii\db\Connection($connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);

		$subdomen_model = Subdomen::find()
			->all();
		foreach ($subdomen_model as $key => $subdomen) {
			$rest_where = [
				'active' => 1,
				'city_id' => $subdomen->city_id
			];

			$current_rest_models = Restaurants::find()
				->with('rooms')
				->select('gorko_id')
				->where($rest_where)
				->all();

			//СУЩЕСТВУЮЩИЕ РЕСТОРАНЫ/ЗАЛЫ МАССИВ
			$current_rest_ids = [];
			$current_room_ids = [];
			foreach ($current_rest_models as $rest_key => $rest) {
				array_push($current_rest_ids, $rest->gorko_id);
				foreach ($rest->rooms as $room_key => $room) {
					array_push($current_room_ids, $room->gorko_id);
				}
			}			

			$api_url = 'https://api.gorko.ru/api/v3/venuecard?list[seed]=1&entity[languageId]=1&list[page]=1&list[perPage]=10000&list[typeId]=1&entity[cityId]='.$subdomen->city_id.'&list[cityId]='.$subdomen->city_id.'&entity%5Bfilters%5D=event%3D1,17,9,11,12,14,15,16,24,25,26,27,28,29,30,32,33,31,34,35,36,37,38,10,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,74,75,76,77,78,80,81,82,83,84,85&list%5Bfilters%5D=event%3D1,17,9,11,12,14,15,16,24,25,26,27,28,29,30,32,33,31,34,35,36,37,38,10,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,74,75,76,77,78,80,81,82,83,84,85';

			//ПЕРВЫЙ ЗАПРОС ПО API			
			$ch_venues = curl_init();
			
			curl_setopt($ch_venues, CURLOPT_HTTPHEADER, array("Cookie: ab_test_venue_city_show=1; ab_test_perfect_venue_samara=1"));
		    curl_setopt($ch_venues, CURLOPT_URL, $api_url);
		    curl_setopt($ch_venues, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch_venues, CURLOPT_ENCODING, '');	    

			$venues = json_decode(curl_exec($ch_venues), true);
			curl_close($ch_venues);

			$gorko_rest_ids = [];
			$gorko_room_ids = [];

			//$page_count = $venues['meta']['totalPages'];

			//ОБРАБОТКА ПУЛА РЕСТОРАНОВ
			foreach ($venues['entity'] as $key => $restaurant) {
				$gorko_rest_ids[$restaurant['id']] = null;
				foreach ($venues['entity'][$key]['room'] as $key => $room) {
					$gorko_room_ids[$room['id']] = null;
				}
			}

			$log = file_get_contents('/var/www/pmnetwork/log/manual_samara_bd.log');
			$log = json_decode($log, true);
			$log[time()] = ['rest_ids' => $gorko_rest_ids, 'api_url' => $api_url];
			$log = json_encode($log);
			file_put_contents('/var/www/pmnetwork/log/manual_samara_bd.log', $log);

			//СБРОС АКТИВНОСТИ РЕСТОРАНОВ ИЗ БАЗЫ
			foreach ($gorko_rest_ids as $id => $value) {
				if (($key = array_search($id, $current_rest_ids)) !== false) {
				    unset($current_rest_ids[$key]);
				}
			}

			foreach ($current_rest_ids as $key => $value) {
				$restaurant = Restaurants::find()
					->where(['gorko_id' => $value])
					->one();
				$restaurant->active = 0;
				$restaurant->save();
			}

			//СБРОС АКТИВНОСТИ ЗАЛОВ ИЗ БАЗЫ
			foreach ($gorko_room_ids as $id => $value) {
				if (($key = array_search($id, $current_room_ids)) !== false) {
				    unset($current_room_ids[$key]);
				}
			}			

			foreach ($current_room_ids as $key => $value) {
				$room = Rooms::find()
					->where(['gorko_id' => $value])
					->one();
				$room->active = 0;
				$room->save();
			}

			//СОЗДАНИЕ ОЧЕРЕДИ ДЛЯ ОБНОВЛЕНИЯ РЕСТОРАНОВ
			foreach ($gorko_rest_ids as $id => $value) {
				$queue_id = Yii::$app->queue->push(new AsyncRenewRestaurants([
					'connection_config' => $connection_config,
					'gorko_id' 	=> $id
				]));
			}

			print_r("$subdomen->city_id - ".count($gorko_rest_ids)."\n");
		}

		return 1;
	}

	public function renewAllImages($connection_config) {
		$connection = new \yii\db\Connection($connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);

		$restaurants = Restaurants::find()
			->select('gorko_id')
			->all();

		foreach ($restaurants as $key => $restaurant) {
			$queue_id = Yii::$app->queue->push(new AsyncRenewImagesExt([
				'connection_config' => $connection_config,
				'gorko_id' 	=> $restaurant->gorko_id
			]));
		}
	}
}