<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\components\AsyncRenewRestaurants;

class GorkoApi extends Model
{
	public function renewAllData($params) {
		foreach ($params as $param) {

			$api_url = 'https://api.gorko.ru/api/v2/directory/venues?'.$param;
			$api_per_page = '&per_page=';
			$api_page = '&page=';
			
			$ch_venues = curl_init();
			$ch_venues_url = $api_url.$api_per_page.'20'.$api_page.'1';
			
			curl_setopt($ch_venues, CURLOPT_HTTPHEADER, array("Cookie: ab_test_venue_city_show=1"));
		    curl_setopt($ch_venues, CURLOPT_URL, $ch_venues_url);
		    curl_setopt($ch_venues, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch_venues, CURLOPT_ENCODING, '');	    

			$venues = json_decode(curl_exec($ch_venues), true);
			curl_close($ch_venues);

			$ids = [];

			foreach ($venues['restaurants'] as $key => $restaurant) {
				$ids[$restaurant['id']] = null;
			}

			$page_count = $venues['meta']['pages_count'];

			$mh = curl_multi_init();
			$channels = [];

			if($page_count > 1){
				for ($i=2; $i <= $page_count; $i++) { 
					$channels[$i] = curl_init();
					$ch_venues_url = $api_url.$api_per_page.'20'.$api_page.$i;
					
					curl_setopt($channels[$i], CURLOPT_HTTPHEADER, array("Cookie: ab_test_venue_city_show=1"));
				    curl_setopt($channels[$i], CURLOPT_URL, $ch_venues_url);
				    curl_setopt($channels[$i], CURLOPT_RETURNTRANSFER,true);
				    curl_setopt($channels[$i], CURLOPT_ENCODING, '');
				    curl_multi_add_handle($mh, $channels[$i]);
				}
			}

			$running = null;
			do {
				curl_multi_exec($mh, $running);
			} while ($running);

			for ($i=2; $i <= $page_count; $i++) {
				curl_multi_remove_handle($mh, $channels[$i]);
			}

			$iter = 0;

			$imgFlag = true;

			foreach ($channels as $channel) {
				$venues = json_decode(curl_multi_getcontent($channel), true);
				foreach ($venues['restaurants'] as $key => $restaurant) {
					$ids[$restaurant['id']] = null;
					$queue_id = Yii::$app->queue->push(new AsyncRenewRestaurants([
						'gorko_id' => $restaurant['id'],
						'dsn' => Yii::$app->db->dsn,
						'imageLoad' => $imgFlag,
					]));
					$imgFlag = false;
					break;
				}
				break;
			}

			curl_multi_close($mh);
		}

		return;
	}
}