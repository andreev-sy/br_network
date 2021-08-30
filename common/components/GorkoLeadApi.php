<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\elastic\LeadLogElastic;

class GorkoLeadApi extends BaseObject{

	public static function send_lead($api_type, $channel, $payload){
		$api_url = 'https://'.$api_type.'/api/'.$channel.'/inquiry/put';

		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        $raw_exec = curl_exec($curl);
        if($raw_exec === false){
            $response = 'Ошибка curl: ' . curl_error($curl);
        }
        else{
            $response = json_decode($raw_exec, true);
        }        
        $info = curl_getinfo($curl);
        curl_close($curl);

        $log_arr = [];
        $log_arr['source']      = $channel;
        $log_arr['api_alias']   = $api_type;
        $log_arr['attempts']   	= 1;
        $log_arr['date']   		= date("Y-m-d H:i:s", time() + (4 * 60 * 60));
        $log_arr['payload']     = isset($response['payload']) ? json_encode($response['payload']) : 'Нет $response[payload]';
        $log_arr['raw_payload'] = json_encode($payload);
        $log_arr['response']    = json_encode($response);
        $log_arr['timestamp']   = time();
        $log_arr['code']        = isset($response['result']) ? json_encode($response['result']) : 'Нет $response[result]';
        $log_arr['name']        = isset($payload['name']) ? $payload['name'] : 'Нет имени';
        $log_arr['phone']       = isset($payload['phone']) ? $payload['phone'] : 'Нет телефона';
        $log_arr['city_id']     = isset($payload['city_id']) ? $payload['city_id'] : 'Нет city_id';
        $log_arr['lead_id']     = isset($response['inquiry']['id']) ? $response['inquiry']['id'] : 0;
        $log_arr['status']      = '';
        $leadLog = new LeadLogElastic();
        $leadLog::addRecord($log_arr);

        return [
            'response' => $response,
            'info' => $info,
            'payload' => $payload,
        ];
	}

	private function send_error($post_data, $e, $channel){
    	$log_arr = [];
        $log_arr['source']      = $channel;
        $log_arr['payload']     = '';
        $log_arr['raw_payload'] = json_encode($post_data);
        $log_arr['response']    = $e->getMessage();
        $log_arr['timestamp']   = time();
        $log_arr['code']        = 'PHP Error';
        $log_arr['name']        = '';
        $log_arr['phone']       = '';
        $log_arr['city_id']     = '';
        $log_arr['lead_id']     = 0;
        $log_arr['status']      = '';

    	$leadLog = new LeadLogElastic();
        $leadLog::addRecord($log_arr);

        return 1;
	}
}