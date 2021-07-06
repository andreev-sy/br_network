<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Subdomen;

class AsyncRenewPhones extends BaseObject implements \yii\queue\JobInterface
{
	public  $gorko_city_id,
		   	$site_connection_config,
		   	$channel_key;

	public function execute($queue) {
		$connection = new \yii\db\Connection($this->site_connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);

		$subdomen = Subdomen::find()->where(['city_id' => $this->gorko_city_id])->one($connection);

		$payload = [
			'city_id' 		=> $this->gorko_city_id,
			'channel_key' 	=> $this->channel_key
		];		
		$curl = curl_init();
		$headers = array();
		$headers[] = 'X-AUTH-TOKEN:J3QQ4-H7H2V-2HCH4-M3HK8-6M8VW';
		curl_setopt($curl, CURLOPT_URL, 'https://v.gorko.ru/api2/sat/channel/phones');
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);

		if($response['result'] == 'ok'){
			$subdomen->phone = $response['phone'];
			$subdomen->save();
		}

		print_r($payload);
		print_r($response);
	}
}