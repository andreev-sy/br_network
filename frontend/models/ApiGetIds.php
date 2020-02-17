<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

class ApiGetIds extends Model
{
	public function getData() {
		$seed = 1;
		
		$ch_venues = curl_init();
		$ch_venues_url = 'https://api.gorko.ru/api/v2/directory/venues?city_id=4400&per_page=20&page=1&type_id=1&type=30,11,17,14&seed='.$seed;
		
	    curl_setopt($ch_venues, CURLOPT_URL, $ch_venues_url);
	    curl_setopt($ch_venues, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch_venues, CURLOPT_ENCODING, '');	    

		$venues = json_decode(curl_exec($ch_venues), true);
		curl_close($ch_venues);

		$ids = [];

		foreach ($venues['restaurants'] as $key => $restaurant) {
			$ids[$restaurant['id']] = null;
		}

		$page_count = 2;
		$page_count = $venues['meta']['pages_count'];

		$mh = curl_multi_init();
		$channels = [];

		if($page_count > 1){
			for ($i=2; $i <= $page_count; $i++) { 
				$channels[$i] = curl_init();
				$ch_venues_url = 'https://api.gorko.ru/api/v2/directory/venues?city_id=4400&per_page=20&page='.$i.'&type_id=1&type=30,11,17,14&seed='.$seed;
				
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

		foreach ($channels as $channel) {
			$venues = json_decode(curl_multi_getcontent($channel), true);
			foreach ($venues['restaurants'] as $key => $restaurant) {
				$ids[$restaurant['id']] = null;
			}
		}

		curl_multi_close($mh);

		ksort($ids);	

		return $ids;
	}
}