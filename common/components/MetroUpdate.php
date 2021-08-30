<?php

namespace common\components;

use Yii;
use common\models\Restaurants;
use common\models\MetroStations;
use common\models\MetroSlice;
use common\models\FilterItems;
use common\models\Slices;

class MetroUpdate {

  private static $request = 'https://geocode-maps.yandex.ru/1.x/?';
  private static $apiKey = '84c84f73-da0f-4da5-ab73-c91c0c210954';


  public static function updateRestaurantClosestMetroStation($restaurantId, $params)
  {
    // $connection = new \yii\db\Connection($params['main_connection_config']);
    // $connection->open();
    // Yii::$app->set('db', $connection);

    $log = '';
    $restaurant = Restaurants::find()->where(['id' => $restaurantId])->one();

    if (!Restaurants::find()->where(['id' => $restaurantId])->exists()){

      $log = 'Ошибка, ресторана с id=' . $restaurantId . ' не существует' . "\n";
      file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

      return false;
    }

    $restaurant->metro_station_id = '';
    $log = 'ресторан: ' . $restaurant->name . "\n";
    file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

    // получение списка ближайших станций
    $closestStationList = self::getClosestMetro($restaurant);

    if (count($closestStationList) === 0) {
      $restaurant->metro_station_id = 0;
      $restaurant->save(false);

      $log = 'найдено станций поблизости: ' . count($closestStationList) . "\n";
      $log .= 'Список станций ресторана ' . $restaurant->name .': ' . $restaurant->metro_station_id . "\n";
      $log .= '--- Обновление ближайших станций ресторана завершено. ---';
      file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

      // Список станций ресторана
      $finalStationString = $restaurant->metro_station_id;

      return '0';
    }

    $log = 'найдено станций поблизости: ' . count($closestStationList) . "\n";
    file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

    $closestStationsIdList = [];
    
    foreach ($closestStationList as $closestStation){

      $log = 'станция: ' . $closestStation['stationName'] . ', ';
      $log .= 'latitude: ' . $closestStation['latitude'] . ', ';
      $log .= 'longitude: ' . $closestStation['longitude'] . "\n";
      file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);


      if (!MetroStations::find()
            ->where(['latitude' => $closestStation['latitude']])
            ->andWhere(['longitude' => $closestStation['longitude']])
            ->exists()
      ){
        $log = 'станция: ' . $closestStation['stationName'] . " не найдена в таблице metro_stations, cоздаём новую строку\n";
        file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

        $newStation = new MetroStations();
        $newStation->city_id = 4400;
        $newStation->name = str_replace('метро ', '', $closestStation['stationName']);
        $newStation->latitude = $closestStation['latitude'];
        $newStation->longitude = $closestStation['longitude'];
        $newStation->alias = MetroUpdate::getTransliterationForUrl(str_replace('метро ', '', $closestStation['stationName']));
        $newStation->save(false);
      }

      $metroStationId = MetroStations::find()
      ->where(['latitude' => $closestStation['latitude']])
      ->andWhere(['longitude' => $closestStation['longitude']])
      ->one()
      ->table_id;

      $log = 'id найденой/созданной станции: ' . $metroStationId . "\n";
      file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

      $closestStationsIdList[] =  $metroStationId;
    }

    $log = 'implode(",", $closestStationsIdList): ' . implode(',', $closestStationsIdList) . "\n";
    file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

    $restaurant->metro_station_id = implode(',', $closestStationsIdList);
    $restaurant->save(false);

    // $connection->close();

    // $connection = new \yii\db\Connection($params['site_connection_config']);
    // $connection->open();
    // Yii::$app->set('db', $connection);

    // $connection->close();

    $log = 'Список станций ресторана ' . $restaurant->name .': ' . $restaurant->metro_station_id . "\n";
    file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);
    $log = '--- Обновление ближайших станций ресторана завершено. ---';
    file_put_contents('/var/www/pmnetwork/frontend/modules/banketnye_zaly_moskva/log.txt', $log . PHP_EOL, FILE_APPEND);

    // Список станций ресторана
    $finalStationString = $restaurant->metro_station_id;

    return $finalStationString;
  }

  public static function getClosestMetro($restaurant)
  {
    // порядок важен - долгота, широта
    $restraurantCoordinates = $restaurant->longitude . ',' . $restaurant->latitude;

    $request = self::$request 
      . 'geocode=' . $restraurantCoordinates 
      . '&apikey=' . self::$apiKey
      . '&sco=longlat'
      . '&kind=metro'
      . '&ll=' . $restraurantCoordinates
      . '&spn=0.04,0.04'  // 1 минута (0.01) ~ 1 километру
      . '&format=json'
      . '&results=3';
    $apiAnswer = json_decode(file_get_contents($request));

    $closestStationList = [];

    foreach ($apiAnswer->response->GeoObjectCollection->featureMember as $station) {

      if (stristr($station->GeoObject->name, 'метро') !== false){
        $tmpStationCoordinates = explode(' ', $station->GeoObject->Point->pos);
        $closestStationList[] = [
          'stationName' => $station->GeoObject->name,
          'longitude' => $tmpStationCoordinates[0],
          'latitude' => $tmpStationCoordinates[1]
        ];
      }
    }

    return $closestStationList;
  }

  public static function updateStationCoords(){
    $allStations = MetroStations::find()->all();
    $stationRequest = '';

    foreach ($allStations as $station){
      $stationQuery = [
        'geocode' => 'метро+' . $station->name,
        'apikey' => self::$apiKey,
        'bbox' => '37.277325,55.497770~37.949632,55.914590',
        'format' => 'json',
        'results' => '1',
      ];
      $stationRequest = self::$request . http_build_query($stationQuery);
      // $apiAnswer = json_decode(file_get_contents($stationRequest));

      $tmpStationCoordinates = explode(' ', $apiAnswer->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);

      $station->yandex_latitude = $tmpStationCoordinates[1];
      $station->yandex_longitude = $tmpStationCoordinates[0];
      $station->yandex_name = str_replace('метро ', '', $apiAnswer->response->GeoObjectCollection->featureMember[0]->GeoObject->name);
      $station->save();
    }
    return true;
  }

  public function refreshMetroSlices()
  {
    foreach (MetroStations::find()->all() as $metroStation){

      if (MetroSlice::find()->where(['title' => $metroStation->name])->exists()){
        $slice = MetroSlice::find()->where(['title' => $metroStation->name])->one();
        $oldList = '';

        if ($slice->same_station_id !==''){
          $oldList = explode(',', $slice->same_station_id);

          if (array_search($metroStation->table_id, $oldList) === false){
            array_push($oldList, $metroStation->table_id);
            $slice->same_station_id = join(',', $oldList);     
            $slice->save(false);
          }
            $slice->save(false);
        } else {
          $slice->same_station_id = $metroStation->table_id;
          $slice->save(false);
        }

      } else {
        $slice = new MetroSlice();
        $slice->alias = $this->getTransliterationForUrl($metroStation->name);
        $slice->active = 1;
        $slice->title = $metroStation->name;
        $slice->same_station_id = $metroStation->table_id;
        $slice->latitude = $metroStation->latitude;
        $slice->longitude = $metroStation->longitude;
        $slice->save(false);
      }
    }

    foreach (MetroSlice::find()->all() as $metroSlice){

      if (FilterItems::find()->where(['value' => $metroSlice->alias])->andWhere(['filter_id' => 6])->exists()){
        $filterItem = FilterItems::find()->where(['value' => $metroSlice->alias])->andWhere(['filter_id' => 6])->one();
        $stationGatesList = explode(',', $metroSlice->same_station_id);
        $filterItem->api_arr = json_encode(["0"=>["table"=>"restaurants","key"=>"metro_stations.id","value"=>$stationGatesList]], JSON_FORCE_OBJECT);
        $filterItem->save(false);
      } else {
        $filterItem = new FilterItems();
        $filterItem->filter_id = 6;
        $filterItem->value = $metroSlice->alias;
        $filterItem->text = $metroSlice->title;
        $stationGatesList = explode(',', $metroSlice->same_station_id);
        $filterItem->api_arr = json_encode(["0"=>["table"=>"restaurants","key"=>"metro_stations.id","value"=>$stationGatesList]], JSON_FORCE_OBJECT);
        $filterItem->active = 1;
        $filterItem->save(false);
      }

      if (!Slices::find()->where(['h1' => $metroSlice->title])->exists()){
        $slice = new Slices();
        $slice->type = 'metro';
        $slice->alias = $metroSlice->alias;
        $slice->h1 = $metroSlice->title;
        $slice->params = '{"metro":"' . $metroSlice->alias . '"}';
        $slice->save(false);
      } else {
        $slice = Slices::find()->where(['h1' => $metroSlice->title])->one();
        $slice->params = '{"metro":"' . $metroSlice->alias . '"}';
        $slice->save(false);
      }
    }
    echo 'Таблицы metro_slice, filter_items, slices успешно обновлены по таблице metro_stations';
    return 1;
  }

  // Ресурсоёмко
  public function refreshMetroSlicesRestaurantCount()
  {
    $restaurants = Restaurants::find()->all();
    $metroSlices = MetroSlice::find()->all();

    foreach (MetroSlice::find()->all() as $slice){
      $slice->restaurants_count = 0;

      foreach (Restaurants::find()->all() as $restaurant){
        $sliceStationList = explode(',', $slice->same_station_id);
        $restaurantStationList = explode(',', $restaurant->metro_station_id);

        if (count(array_intersect($sliceStationList, $restaurantStationList)) > 0){
          $slice->restaurants_count = $slice->restaurants_count + 1;
        }
      }

      $slice->save(false);
    }
    echo 'Количество ресторанов у срезов по станциям метро обновлено';
    exit;
  }

  public static function getTransliterationForUrl($name)
  {
    $latin = array('-', "Sch", "sch", 'Yo', 'Zh', 'Kh', 'Ts', 'Ch', 'Sh', 'Yu', 'ya', 'yo', 'zh', 'kh', 'ts', 'ch', 'sh', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', '', 'Y', '', 'E', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', '', 'y', '', 'e');
    $cyrillic = array(' ', "Щ", "щ", 'Ё', 'Ж', 'Х', 'Ц', 'Ч', 'Ш', 'Ю', 'я', 'ё', 'ж', 'х', 'ц', 'ч', 'ш', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ь', 'Ы', 'Ъ', 'Э', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ь', 'ы', 'ъ', 'э');
    return trim(
      preg_replace(
        "/(.)\\1+/",
        "$1",
        strtolower(
          preg_replace(
            "/[^a-zA-Z0-9-]/",
            '',
            str_replace($cyrillic, $latin, $name)
          )
        )
      ),
      '-'
    );
  }
}
