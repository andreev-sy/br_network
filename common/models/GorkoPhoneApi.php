<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\premium\PremiumRest;

class GorkoPhoneApi extends Model
{
	public function renewAllPhones($connection_config) {
		$connection = new \yii\db\Connection($connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);

		$premium_rest_all = PremiumRest::find()
			->where(['active' => 1])
			->with('channelInfo')
			->all();

		foreach ($premium_rest_all as $premium_rest) {
			if($premium_rest->premium_phone && !$premium_rest->proxy_phone){
				$ch_phone = curl_init();

				$api_url = 'https://v.gorko.ru/api/sat/priority_phone/put';
				$headers = ['X-AUTH-TOKEN:J3QQ4-H7H2V-2HCH4-M3HK8-6M8VW'];
				$payload = [
					'restaurant_gorko_id' 			=> $premium_rest->gorko_id,
					'channel_vgorko_id' 			=> $premium_rest->channelInfo->gorko_id,
					'restaurant_phone_for_calls'	=> $premium_rest->premium_phone,
					'restaurant_phone_for_sms'		=> $premium_rest->premium_phone
				];
			
				curl_setopt($ch_phone, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch_phone, CURLOPT_POSTFIELDS, $payload);
			    curl_setopt($ch_phone, CURLOPT_URL, $api_url);
			    curl_setopt($ch_phone, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch_phone, CURLOPT_ENCODING, '');	    

				$proxy_phone_api = json_decode(curl_exec($ch_phone), true);
				curl_close($ch_phone);
				print_r($proxy_phone_api);

				if(isset($proxy_phone_api['result'])
				   && $proxy_phone_api['result'] == 'ok'
				   && isset($proxy_phone_api['slot']) 
				   && isset($proxy_phone_api['slot']['phone']) 
				   && isset($proxy_phone_api['slot']['phone']['number'])
				   && $proxy_phone_api['slot']['phone']['number']){
					$premium_rest->proxy_phone = $proxy_phone_api['slot']['phone']['number'];
					$premium_rest->save();
				}
			}
			//echo $premium_rest->channelInfo->gorko_id.PHP_EOL;
		}

		return 1;
	}
}