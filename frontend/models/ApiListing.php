<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\components\TransformParams;

class ApiListing extends Model
{
	public function getData($request) {
		$rand_seed = random_int(1, 999999);
		$session = Yii::$app->session;
		$session_seed = $session->get('seed');
		if($session_seed){
			$seed = $session_seed;
		}
		else{
			$session->set('seed', $rand_seed);
			$seed = $rand_seed;
		}
		
		$ch_venues = curl_init();
		$ch_venues_url = 'https://api.gorko.ru/api/v2/directory/venues?city_id=4400&type_id=1&type=30,11,17,14&fields=address,capacity,params&cover_size=440x250x1&seed='.$seed.$request;
		
	    curl_setopt($ch_venues, CURLOPT_URL, $ch_venues_url);
	    curl_setopt($ch_venues, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch_venues, CURLOPT_ENCODING, '');

	    $ch_filter = curl_init();
	    curl_setopt($ch_filter, CURLOPT_URL, 'https://api.gorko.ru/api/v2/directory/filters?city_id=4400&type=restaurants&type_id=1');
	    curl_setopt($ch_filter, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch_filter, CURLOPT_ENCODING, '');

	    $mh = curl_multi_init();
		curl_multi_add_handle($mh, $ch_venues);
		curl_multi_add_handle($mh, $ch_filter);

		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);

		curl_multi_remove_handle($mh, $ch_venues);
		curl_multi_remove_handle($mh, $ch_filter);
		curl_multi_close($mh);

		$venues = json_decode(curl_multi_getcontent($ch_venues), true);

		foreach ($venues['restaurants'] as $key => $restaurant) {
			$venues['restaurants'][$key]['listing_params'] = TransformParams::restaurant($restaurant);
		}
		

		return [
			'meta' => $venues['meta'],
			'restaurants' => $venues['restaurants'],
			'filters' => json_decode(curl_multi_getcontent($ch_filter), true)['filters']
		];
	}
}