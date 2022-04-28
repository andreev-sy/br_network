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
use common\models\Filter;
use common\models\Slices;
use frontend\components\QueryFromSlice;
use common\models\elastic\ItemsFilterElastic;
use frontend\modules\banketnye_zaly_moskva\components\UpdateFilterItems;
use common\models\SlicesExtra;

class GorkoconsoleController extends Controller
{
	//ОБНОВЛЕНИЕ ROOT БАЗЫ ИЗ GORKO API
	public function actionRenewAllData()
	{
		$mysql_config =	\Yii::$app->params['mysql_config'];
		$main_config = \Yii::$app->params['main_api_config'];
		$connection_config = array_merge($mysql_config, $main_config['mysql_config']);

		GorkoApi::renewAllData($connection_config);

		return 1;
	}

	//РАБОТА С ELASTIC-ОМ НА МОДУЛЯХ
	public function actionElasticDelete($site)
	{
		$connectionAndModel = $this->moduleAttr($site);

		$connectionAndModel['elasticModel']::deleteIndex();
		return 1;
	}

	public function actionElasticRefresh($site)
	{
		$connectionAndModel = $this->moduleAttr($site);
		$params = [
			'main_connection_config' 	=> $connectionAndModel['main_connection_config'],
			'site_connection_config' 	=> $connectionAndModel['site_connection_config'],
			'watermark' 			 			 	=> $connectionAndModel['site_config']['params']['watermark'],
			'imageHash' 			 				=> $connectionAndModel['site_config']['params']['imageHash'],
			'watermark_pos' 		 			=> $connectionAndModel['site_config']['params']['watermark_pos'],
			'elasticModel'			 			=> $connectionAndModel['site_config']['params']['module_path'].'\models\ElasticItems',
			'watermark_city' 			 		=> isset($connectionAndModel['site_config']['params']['watermark_city']) ? $connectionAndModel['site_config']['params']['watermark_city'] : false,
			'slug_city' 			 				=> isset($connectionAndModel['site_config']['params']['slug_city']) ? $connectionAndModel['site_config']['params']['slug_city'] : false,
		];

		if ($connectionAndModel['site_config']['params']['subdomens']) {
			if ($connectionAndModel['elasticModel']::refreshIndex($params)) {
				$this->actionSubdomenCheck($site);
			}
		}
		else{
			$connectionAndModel['elasticModel']::refreshIndex($params);
		}
		return 1;
	}

	public function actionElasticSearchRefresh($site)
	{
		$connectionAndModel = $this->moduleAttr($site);
		$params = [
			'main_connection_config' 	=> $connectionAndModel['main_connection_config'],
			'site_connection_config' 	=> $connectionAndModel['site_connection_config'],
			'elasticModel'			 			=> $connectionAndModel['site_config']['params']['module_path'].'\models\ElasticSearch',
		];

		$elasticSearchPath = $connectionAndModel['site_config']['params']['module_path'].'\models\ElasticSearch';
		$elasticSearchModel = new $elasticSearchPath();

		$elasticSearchModel::refreshIndex($params);

		return 1;
	}

	public function actionElasticUpdate($site)
	{
		$connectionAndModel = $this->moduleAttr($site);
		$params = [
			'main_connection_config' 	=> $connectionAndModel['main_connection_config'],
			'site_connection_config' 	=> $connectionAndModel['site_connection_config'],
			'watermark' 			 			 	=> $connectionAndModel['site_config']['params']['watermark'],
			'imageHash' 			 				=> $connectionAndModel['site_config']['params']['imageHash'],
			'watermark_pos' 		 			=> $connectionAndModel['site_config']['params']['watermark_pos'],
			'elasticModel'			 			=> $connectionAndModel['site_config']['params']['module_path'].'\models\ElasticItems',
			'watermark_city' 			 		=> isset($connectionAndModel['site_config']['params']['watermark_city']) ? $connectionAndModel['site_config']['params']['watermark_city'] : false,
			'slug_city' 			 				=> isset($connectionAndModel['site_config']['params']['slug_city']) ? $connectionAndModel['site_config']['params']['slug_city'] : false,
		];

		if ($connectionAndModel['site_config']['params']['subdomens']) {
			if ($connectionAndModel['elasticModel']::updateIndex($params)) {
				$this->actionSubdomenCheck($site);
			}
		}
		else{
			$connectionAndModel['elasticModel']::updateIndex($params);
		}
		return 1;
	}

	//ПРОВЕРКА ПОДДОМЕНОВ НА НУЖНОЕ КОЛ-ВО РЕСТОВ
	public function actionSubdomenCheck($site)
	{
		$connectionAndModel = $this->moduleAttr($site);
		
		$connection = new \yii\db\Connection($connectionAndModel['site_connection_config']);
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
		return 1;
	}

	//СБОРЩИК CONNECTION И МОДЕЛИ ИЗ КОНФИГОВ МОДУЛЯ
	private function moduleAttr($site){
		if(!isset(\Yii::$app->params['module_api_config'][$site])){
			print_r('Нет конфига под '.$site);
			exit;
		}

		$site_config = \Yii::$app->params['module_api_config'][$site];

		$elasticItemsPath = $site_config['params']['module_path'].'\models\ElasticItems';
		$elasticModel = new $elasticItemsPath();

		$mysql_config =	\Yii::$app->params['mysql_config'];
		$main_config = \Yii::$app->params['main_api_config'];
		$site_connection_config = array_merge($mysql_config, $site_config['mysql_config']);
		$main_connection_config = array_merge($mysql_config, $main_config['mysql_config']);

		return [
			'site_config'			 => $site_config,
			'main_connection_config' => $main_connection_config,
			'site_connection_config' => $site_connection_config,
			'elasticModel' 			 => $elasticModel,
		];
	}

	public function actionGetCityPhones($site)
	{
		$connectionAndModel = $this->moduleAttr($site);

		$connection = new \yii\db\Connection($connectionAndModel['site_connection_config']);
		$connection->open();
		Yii::$app->set('db', $connection);

		$subdomen_model = Subdomen::find()
			->all($connection);

		foreach ($subdomen_model as $key => $subdomen) {
			$queue_id = Yii::$app->queue->push(new AsyncRenewPhones([
				'gorko_city_id'			 => $subdomen->city_id,
				'site_connection_config' => $connectionAndModel['site_connection_config'],
				'channel_key' 			 => $connectionAndModel['site_config']['params']['gorko_api']['phone_key']
			]));
		}
	}

	public function actionGetCityPhone($site)
	{
		$connectionAndModel = $this->moduleAttr($site);
		
		$queue_id = Yii::$app->queue->push(new AsyncRenewPhones([
			'gorko_city_id'			 => $connectionAndModel['site_config']['params']['gorko_api']['city'],
			'site_connection_config' => $connectionAndModel['site_connection_config'],
			'channel_key' 			 => $connectionAndModel['site_config']['params']['gorko_api']['phone_key']
		]));
	}

	public function actionGetAllImages()
	{
		$mysql_config =	\Yii::$app->params['mysql_config'];
		$main_config = \Yii::$app->params['main_api_config'];
		$connection_config = array_merge($mysql_config, $main_config['mysql_config']);

		GorkoApi::renewAllImages($connection_config);

		return 1;
	}

























	// ОБНОВИТЬ КОЛИЧЕСТВО РЕСТОРАНОВ У СРЕЗОВ ДЛЯ banketnye-zaly-moskva.ru 
	
	public function actionRefreshRestCount(){
		$connection_bzm = new \yii\db\Connection([
			'dsn' => 'mysql:host=localhost;dbname=pmn_bzm',
			'username' => 'root',
			'password' => 'GxU25UseYmeVcsn5Xhzy',
			'charset' => 'utf8',
		]);
		$connection_bzm->open();
		Yii::$app->set('db', $connection_bzm);

		$filter_model = Filter::find()->with('items')->orderBy(['sort' => SORT_ASC])->all($connection_bzm);
    $slices_model = Slices::find()->all($connection_bzm);
    $elastic_model = new \frontend\modules\banketnye_zaly_moskva\models\ElasticItems;

    foreach ($slices_model as $slice){
      $slice_obj = new QueryFromSlice($slice->alias);
      $params = UpdateFilterItems::parseGetQuery($slice_obj->params, $filter_model, $slices_model);
      $items = new ItemsFilterElastic($params['params_filter'], 1, 1, false, 'restaurants', $elastic_model, false, false, false, true);
      

      if (SlicesExtra::find()->where(['slices_id' => $slice->id])->exists($connection_bzm)){
        $totalItem = SlicesExtra::find()->where(['slices_id' => $slice->id])->one($connection_bzm);
        $totalItem->restaurant_count = $items->total;
        $totalItem->save();
      } else {
        $totalItem = new SlicesExtra($connection_bzm);
        $totalItem->slices_id = $slice->id;
        $totalItem->restaurant_count = $items->total;
        $totalItem->save();
      }
    }

		$log = "";

		date_default_timezone_set('Europe/Moscow');
		$log .= date('d\.m\.Y H:i:s', time()) . "\n";
		$log .= 'Количество ресторанов у всех срезов обновлено' . "\n";
		$log .= "\n";

		file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log/updateActiveSlices.log', $log, FILE_APPEND);

    // echo 'Количество ресторанов у всех срезов обновлено';
    exit;
	}

	// ДЕАКТИВИРОВАТЬ СРЕЗЫ БЕЗ РЕСТОРАНОВ ДЛЯ banketnye-zaly-moskva.ru 

	public function actionRefreshActiveFilterItems(){
		$connection_bzm = new \yii\db\Connection([
			'dsn' => 'mysql:host=localhost;dbname=pmn_bzm',
			'username' => 'root',
			'password' => 'GxU25UseYmeVcsn5Xhzy',
			'charset' => 'utf8',
		]);
		$connection_bzm->open();
		Yii::$app->set('db', $connection_bzm);

		$slices_model = Slices::find()->with('slicesExtra')->all($connection_bzm);

    foreach ($slices_model as $slice){
      $filterItem = FilterItems::find()->where(['value' => $slice->alias])->one($connection_bzm);

      if ($slice->slicesExtra->restaurant_count > 0){
        $filterItem->active = 1;
      } elseif ($slice->slicesExtra->restaurant_count === 0){
        $filterItem->active = 0;
      }
      $filterItem->save();
    }

		$log = "";

		date_default_timezone_set('Europe/Moscow');
		$log .= date('d\.m\.Y H:i:s', time()) . "\n";
		$log .= 'Срезы без ресторанов деактивированы' . "\n";
		$log .= "\n";

		file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log/updateActiveSlices.log', $log, FILE_APPEND);

    // echo 'Срезы без ресторанов деактивированы';
    exit;
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
				'password' => 'GxU25UseYmeVcsn5Xhzy',
				'charset' => 'utf8',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);
			(new GorkoApi())->showAllData([$siteArr[$site]['params']]);
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
			'password' => 'GxU25UseYmeVcsn5Xhzy',
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
		curl_setopt($curl, CURLOPT_URL, 'https://v.gorko.ru/api2/');
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
			'key' 		=> 'banketyvspb',
			'name' 	=> 'Сателлит банкеты в Санкт-Петербурге'
		];

		$headers[] = 'X-AUTH-TOKEN:J3QQ4-H7H2V-2HCH4-M3HK8-6M8VW';
		curl_setopt($curl, CURLOPT_URL, 'https://v.gorko.ru/api2/sat/channel');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    print_r($response);
    exit;
	}
}
