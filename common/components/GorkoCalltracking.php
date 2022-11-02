<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\elastic\LeadLogElastic;

class GorkoCalltracking extends BaseObject
{

	public static function send_lead($payload)
	{
		$api_url = 'https://api.gorko.ru/api/v2/conversions';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		$raw_exec = curl_exec($curl);
		if ($raw_exec === false) {
			$response = 'Ошибка curl: ' . curl_error($curl);
		} else {
			$response = json_decode($raw_exec, true);
		}
		$info = curl_getinfo($curl);
		curl_close($curl);

		return [
			'response' => $response,
			'info' => $info,
			'payload' => $payload,
		];
	}
}
