<?php

namespace console\controllers;

use common\models\elastic\FilterQueryConstructorElastic;
use common\models\FilterItems;
use Yii;
use yii\console\Controller;
use common\models\GorkoApi;
use common\models\Subdomen;
use common\models\Restaurants;
use common\models\Rooms;
use common\components\MetroUpdate;
use frontend\modules\pmnbd\models\ElasticItems;
use frontend\modules\pmnbd\models\SubdomenFilteritem;
use common\components\AsyncRenewPhones;

class GorkoconsoleController extends Controller
{
	public $siteArr = [
		'birthday' => [
			'params' 	=> [
				'params' 		=> 'entity[cityId]={{city_id}}&list[cityId]={{city_id}}&entity[filters]=event%3D9&list[filters]=event%3D9&entity[specId]=9',
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark.png',
				'imageHash' 	=> 'birthdaypmn',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_bd',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\pmnbd\models\ElasticItems',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'channel_key'	=> 'birthday-place',
					'city' 			=> false,
				],
			]
		],
		'banket' => [
			'params' 	=> [
				'params' 		=> 'city_id=4400&type_id=1',
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-bzm.png',
				'imageHash' 	=> 'banketmoscow',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_bzm',
				'subdomens' 	=> false,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\banketnye_zaly_moskva\models\ElasticItems',
				'only_comm'		=> true,
			]
		],
		'drnaprirode' => [
			'params' 	=> [
				'params' 		=> 'entity[cityId]={{city_id}}&list[cityId]={{city_id}}',
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-drnaprirode.png',
				'imageHash' 	=> 'drnaprirode',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_priroda_dr',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\priroda_dr\models\ElasticItems',
				'only_comm'		=> false,
				'gorko_api'		=> [
					'channel_key'	=> 'drnaprirode',
					'city' 			=> false,
				],
			]
		],
		'arenda' => [
			'params' 	=> [
				'params' 		=> 'entity[cityId]={{city_id}}&list[cityId]={{city_id}}',
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-arenda.png',
				'imageHash' 	=> 'arenda',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_arenda',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\arenda\models\ElasticItems',
				'only_comm'		=> false,
			]
		],
		'graduation' => [
			'params' 	=> [
				'params' 		=> 'entity[cityId]={{city_id}}&list[cityId]={{city_id}}&entity[filters]=event%3D11&list[filters]=event%3D11&entity[specId]=11',
				'watermark' 	=> false,
				'imageHash' 	=> 'graduation',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_graduation',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\graduation\models\ElasticItems',
				'only_comm'		=> true,
			]
		],
		'topbanket' => [
			'params' 	=> [
				'params' 		=> 'entity[cityId]={{city_id}}&list[cityId]={{city_id}}&entity[filters]=event%3D11&list[filters]=event%3D11&entity[specId]=11',
				'watermark' 	=> false,
				'imageHash' 	=> 'topbanket',
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_top_banket',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'elasticModel' 	=> 'frontend\modules\top_banket\models\ElasticItems',
				'only_comm'		=> true,
			]
		]
	];

	public function actionRenewAllData($site)
	{
		$siteArr = $this->siteArr;
		if (!array_key_exists($site, $siteArr)) {
			return 0;
		}
		if ($siteArr[$site]['params']['subdomens']) {
			$connection = new \yii\db\Connection([
				'dsn' 		=> $siteArr[$site]['params']['dsn'],
				'username' => 'root',
				'password' => 'Gkcfmdsop',
				'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

			$subdomen_model = Subdomen::find()
				->all($connection);
			foreach ($subdomen_model as $key => $subdomen) {
				$subdomenArr = $siteArr;
				$subdomenArr[$site]['params']['params']   = str_replace('{{city_id}}', $subdomen->city_id, $subdomenArr[$site]['params']['params']);
				$subdomenArr[$site]['params']['subdomen'] = $subdomen->city_id;
				$gorko_api = GorkoApi::renewAllData([$subdomenArr[$site]['params']]);
				print_r($gorko_api);
			}
		} else {
			GorkoApi::renewAllData([$siteArr[$site]['params']]);
		}


		return 'Понеслась ёбка';
	}

	public function actionSubdomenCheck($site)
	{
		$siteArr = $this->siteArr;
		if (!array_key_exists($site, $siteArr)) {
			return 0;
		} else {
			$connection = new \yii\db\Connection([
				'dsn' 		=> $siteArr[$site]['params']['dsn'],
				'username' => 'root',
				'password' => 'Gkcfmdsop',
				'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);
			if($site == 'birthday'){
				SubdomenFilteritem::deactivate();
				$counterActive = 0;
				$counterInactive = 0;
				foreach (Subdomen::find()->all() as $key => $subdomen) {
					$isActive = Restaurants::find()->where(['city_id' => $subdomen->city_id])->count() > 9;
					$subdomen->active = $isActive;
					$subdomen->save();
					if ($subdomen->active) {
						foreach (FilterItems::find()->all() as $filterItem) {
							$hits = $this->getFilterItemsHitsForCity($filterItem, $subdomen->city_id);
							$where = ['subdomen_id' => $subdomen->id, 'filter_items_id' => $filterItem->id];
							$subdomenFilterItem = SubdomenFilteritem::find()->where($where)->one() ?? new SubdomenFilteritem($where);
							$subdomenFilterItem->hits = $hits;
							$subdomenFilterItem->is_valid = 1;
							$subdomenFilterItem->save();
							$hits > 0 ? $counterActive++ : $counterInactive++;
						}
					}
				}
				foreach (Rooms::find()->where(['like', 'cover_url', 'no_photo'])->all() as $room) {
					$room->cover_url = '/img/bd/no_photo_s.png';
					$room->save();
				}
				echo "active=$counterActive; inactive=$counterInactive";
			}
			else{
				foreach (Subdomen::find()->all() as $key => $subdomen) {
					$isActive = Restaurants::find()->where(['city_id' => $subdomen->city_id])->count() > 9;
					$subdomen->active = $isActive;
					$subdomen->save();
				}
			}				
		}
		return 1;
	}


	public function actionShowAllData($site)
	{
		$siteArr = $this->siteArr;
		if (!array_key_exists($site, $siteArr)) {
			return 0;
		} else {
			$connection = new \yii\db\Connection([
				'dsn' 		=> $siteArr[$site]['params']['dsn'],
				'username' => 'root',
				'password' => 'Gkcfmdsop',
				'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);
			(new GorkoApi())->showAllData([$siteArr[$site]['params']]);
		}
	}

	public function actionElasticRefresh($site)
	{
		$siteArr = $this->siteArr;
		if (!array_key_exists($site, $siteArr)) {
			return 0;
		}
		$elasticItems = $siteArr[$site]['params']['elasticModel'];
		$elasticItemsClass = new $elasticItems();
		if ($siteArr[$site]['params']['subdomens']) {
			if ($elasticItemsClass::refreshIndex()) {
				$this->actionSubdomenCheck($site);
			}
		}
		else{
			$elasticItemsClass::refreshIndex();
		}
	}

	private function getFilterItemsHitsForCity($filterItem, $city_id)
	{
		$filter_item_arr = json_decode($filterItem->api_arr, true);
		$main_table = 'restaurants';
		$simple_query = [];
		$nested_query = [];
		$type_query = [];
		$location_query = [];
		foreach ($filter_item_arr as $filter_data) {

			$filter_query = new FilterQueryConstructorElastic($filter_data, $main_table);

			if ($filter_query->nested) {
				if (!isset($nested_query[$filter_query->query_type])) {
					$nested_query[$filter_query->query_type] = [];
				}
			} elseif ($filter_query->type) {
				if (!isset($type_query[$filter_query->query_type])) {
					$type_query[$filter_query->query_type] = [];
				}
			} elseif ($filter_query->location) {
				if (!isset($location_query[$filter_query->query_type])) {
					$location_query[$filter_query->query_type] = [];
				}
			} else {
				if (!isset($simple_query[$filter_query->query_type])) {
					$simple_query[$filter_query->query_type] = [];
				}
			}

			foreach ($filter_query->query_arr as $filter_value) {
				if ($filter_query->nested) {
					array_push($nested_query[$filter_query->query_type], $filter_value);
				} elseif ($filter_query->type) {
					array_push($type_query[$filter_query->query_type], $filter_value);
				} elseif ($filter_query->location) {
					array_push($location_query[$filter_query->query_type], $filter_value);
				} else {
					array_push($simple_query[$filter_query->query_type], $filter_value);
				}
			}
		}
		$final_query = [
			'bool' => [
				'must' => [],
			]
		];
		array_push($final_query['bool']['must'], ['match' => ['restaurant_city_id' => $city_id]]);
		foreach ($simple_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
		}

		foreach ($nested_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			if ($main_table == 'rooms') {
				array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
			} else {
				array_push($final_query['bool']['must'], ['nested' => ["path" => "rooms", "query" => ['bool' => ['must' => ['bool' => ['should' => $temp_type_arr]]]]]]);
			}
		}

		foreach ($type_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			if ($main_table == 'rooms') {
				array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
			} else {
				array_push($final_query['bool']['must'], ['nested' => ["path" => "restaurant_types", "query" => ['bool' => ['must' => ['bool' => ['should' => $temp_type_arr]]]]]]);
			}
		}

		foreach ($location_query as $type => $arr_filter) {
			$temp_type_arr = [];
			foreach ($arr_filter as $key => $value) {
				array_push($temp_type_arr, $value);
			}
			if ($main_table == 'rooms') {
				array_push($final_query['bool']['must'], ['bool' => ['should' => $temp_type_arr]]);
			} else {
				array_push($final_query['bool']['must'], ['nested' => ["path" => "restaurant_location", "query" => ['bool' => ['must' => ['bool' => ['should' => $temp_type_arr]]]]]]);
			}
		}
		$final_query = [
			"function_score" => [
				"query" => $final_query,
				"functions" => [
					[
						"filter" => ["match" => ["restaurant_commission" => "2"]],
						"random_score" => [],
						"weight" => 100
					],
				]
			]
		];

		$query = (new ElasticItems())::find()->query($final_query)->limit(0);

		return $query->search()['hits']['total'];
	}

	public function actionClear()
	{

		$connection = new \yii\db\Connection([
			'dsn' 		=> 'mysql:host=localhost;dbname=pmn_bd',
			'username' => 'root',
			'password' => 'Gkcfmdsop',
			'charset' => 'utf8',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);
		$restaurants = Restaurants::find()->all($connection);
		foreach ($restaurants as $key => $value) {
			$value->location = null;
			$value->save();
		}
		echo count($restaurants);
	}

	public function actionRefreshMetroSlices()
	{
		return MetroUpdate::refreshMetroSlices();
	}

	public function actionRefreshMetroSlicesRestaurantCount()
	{
		return MetroUpdate::refreshMetroSlicesRestaurantCount();
	}

	public function actionCheckPhonesApi()
	{
		$curl = curl_init();
		$headers = array();
		$headers[] = 'X-AUTH-TOKEN:J3QQ4-H7H2V-2HCH4-M3HK8-6M8VW';
		curl_setopt($curl, CURLOPT_URL, 'https://v.wedding.net/api2/');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl, CURLOPT_ENCODING, '');
	    $response = json_decode(curl_exec($curl), true);
	    curl_close($curl);
	    print_r($response);
	    exit;
	}

	public function actionApiNewChannel()
	{
		$curl = curl_init();
		$headers = array();
		$payload = [
			'key' 		=> 'banket_wedding_gurugram',
			'name' 	=> 'Лэндинг банкетов в Gurugram'
		];

		$headers[] = 'X-AUTH-TOKEN:J3QQ4-H7H2V-2HCH4-M3HK8-6M8VW';
		curl_setopt($curl, CURLOPT_URL, 'https://v.wedding.net/api2/sat/channel');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl, CURLOPT_ENCODING, '');
	    $response = json_decode(curl_exec($curl), true);
	    curl_close($curl);
	    print_r($response);
	    exit;
	}

	public function actionGetCityPhones($site)
	{
		$siteArr = $this->siteArr;
		if (!array_key_exists($site, $siteArr)) {
			return 0;
		}

		if($siteArr[$site]['params']['subdomens']){
			$connection = new \yii\db\Connection([
				'dsn' 		=> $siteArr[$site]['params']['dsn'],
				'username' => 'root',
				'password' => 'Gkcfmdsop',
				'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

			$subdomen_model = Subdomen::find()
				->all($connection);

			foreach ($subdomen_model as $key => $subdomen) {
				$subdomenArr = $siteArr;
				$queue_id = Yii::$app->queue->push(new AsyncRenewPhones([
					'gorko_city_id'	=> $subdomen->city_id,
					'dsn' 			=> $siteArr[$site]['params']['dsn'],
					'channel_key' 	=> $siteArr[$site]['params']['gorko_api']['channel_key']
				]));
			}
		}
	}
}
