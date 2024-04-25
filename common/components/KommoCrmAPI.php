<?php

namespace common\components;

use Yii;
use yii\log\Logger;
use yii\log\FileTarget;

class KommoCrmAPI
{
    protected $redirect_uri;
    protected $api;
    protected $client_id;
    protected $client_secret;
    protected $code;
    protected $token_path;
    protected $cookie_path;
    protected $log_path;
    protected $use_long_term_token;
    protected $token;
    protected $debug;
    public $params = [];
    public $sleep_on_limit_reached;
    public $sleep_interval;

    public function __construct($params, $use_long_term_token = false, $debug = false, $sleep_on_limit_reached = null, $sleep_interval = 0)
    {
        $this->params = $params;
        $this->sleep_on_limit_reached = $sleep_on_limit_reached;
        $this->sleep_interval = $sleep_interval;
        $this->debug = $debug; 
        $this->redirect_uri = $this->params['redirect_uri'];
        $this->api = $this->params['api'];
        $this->client_id = $this->params['client_id'];
        $this->client_secret = $this->params['client_secret'];
        $this->code = $this->params['code'];
        $this->token_path = $this->params['token_path'];
        $this->cookie_path = $this->params['cookie_path'];
        $this->log_path = $this->params['log_path'];

        if(strtotime($this->params['long_term_token_date_expired']) > time()){
            $use_long_term_token = false;
            $this->logger('Срок действия долгосрочного токен истек, обновите долгосрочный токен');
        }

        $this->use_long_term_token = $use_long_term_token;
        $this->token = $use_long_term_token ? $this->params['long_term_token'] : '';
       
        
        // $logger = Yii::$app->get('log');
        // $logger->targets['kommoApiLogger'] = new FileTarget([
        //     'logFile' => $this->log_path,
        //     'levels' => ['error', 'warning', 'info'],
        // ]);
        
        // echo '<pre>';
        // var_dump(Yii::error('Чек лога', 'kommoApiLogger'));
        // die;;
    }

    public function logger($text)
    {
        $log = date('Y-m-d H:i:s') . ' ' . $text;

        // Yii::info($log . PHP_EOL, 'kommoApiLogger');

        if($this->debug) echo $log.'<br>';
    }

    public function checkTokenExpiration() {
        // если токен долгосрочный
        if(!empty($this->token) and $this->use_long_term_token) 
            return true; 

        if(!file_exists($this->token_path)){
            if($this->auth()) $this->logger('Авторизация прошла успешно');
            else $this->logger('Не удалось пройти авторизацию');
        }

        /* получаем значения токенов из файла */
        $dataToken = file_get_contents($this->token_path);
        $dataToken = json_decode($dataToken, true);

        /* проверяем, истёкло ли время действия токена Access */
        if ($dataToken["endTokenTime"] < time()) {
            /* запрашиваем новый токен */
            $dataToken = $this->returnNewToken($dataToken["refresh_token"]);
            $newAccess_token = $dataToken["access_token"];
            $this->logger('Новый токен');
        } else {
            $newAccess_token = $dataToken["access_token"];
        }

        $this->token = $newAccess_token;
    }

    public function auth()
    {
        $params = [
            'client_id' => $this->client_id,
            // id нашей интеграции
            'client_secret' => $this->client_secret,
            // секретный ключ нашей интеграции
            'grant_type' => 'authorization_code',
            'code' => $this->code,
            // код авторизации нашей интеграции
            'redirect_uri' => $this->redirect_uri,
            // домен сайта нашей интеграции
        ];

        $response = $this->request('post', '/oauth2/access_token', $params, false);

        if (!empty($response) and !isset($response['error'])) {
            $response['endTokenTime'] = $response['expires_in'] + time();

            /* передаём значения наших токенов в файл */
            $f = fopen($this->token_path, 'w');
            fwrite($f, json_encode($response));
            fclose($f);

            return $response;
        } else {
            return false;
        }
    }

    public function returnNewToken($refresh_token)
    {
        $params = [
            'client_id' => $this->client_id,
            // id нашей интеграции
            'client_secret' => $this->client_secret,
            // секретный ключ нашей интеграции
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'redirect_uri' => $this->redirect_uri,
        ];

        $response = $this->request('post', '/oauth2/access_token', $params, false);

        if (!empty($response) and !isset($response['error'])) {
            /* записываем конечное время жизни токена */
            $response["endTokenTime"] = time() + $response["expires_in"];

            /* передаём значения наших токенов в файл */
            $f = fopen($this->token_path, 'w');
            fwrite($f, json_encode($response));
            fclose($f);

            return $response;
        } else {
            return false;
        }
    }

    public function request( $method, $action, $params = [], $checkToken = true ) {	
        if($checkToken) $this->checkTokenExpiration();

        $params_string = http_build_query( $params );
		$params_json = json_encode( $params );
		$url = $this->api.$action;

        $this->logger('Send request '.$url);
        $this->logger('With method '.$method);
        $this->logger('With params '.$params_json);
        if($this->use_long_term_token) $this->logger('Use long term token');
        else $this->logger('Use jwt token');
        
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        if(!empty($this->token)) 
            $headers[] = 'Authorization: Bearer ' . $this->token;

        $curl = curl_init();
		
		if( $method == 'post' ) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, "KammoCRM-API-client-undefined/2.0");
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params_json);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_path);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_path);
		}
		else if( $method == 'get'  ) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url.'?'.$params_string );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT,'KommoCRM-oAuth-client/1.0');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		}
		else if( $method == 'put'  ) {
            $curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($curl, CURLOPT_USERAGENT,'KommoCRM-oAuth-client/1.0');
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params_json);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_path);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_path);	
		}
		else if( $method == 'delete'  ) {
            $curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($curl, CURLOPT_USERAGENT,'KommoCRM-oAuth-client/1.0');
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params_json);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_path);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_path);
		}

        try {
            $out = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE );
            $code = (int)$code;
            $msg = http_response_code($code);
            curl_close($curl);

            $this->logger($code.' '.http_response_code($msg));

            if ( $code == 503 and $this->sleep_on_limit_reached ) {
                $this->logger("KOMMO API - IS LIMIT REACHED!");
                sleep($this->sleep_interval);
                return $this->request($method, $action, $params, $checkToken);
            }
            
            $this->logger('Response '.$out);

            if( $code == 200 or $code == 201 )
                return json_decode( $out, true );
            
            return [
                "status"  => "error", 
                "error" => $msg, 
                "error_code" => $code, 
                "response" => json_decode( $out, true )
            ];
        } catch (\Exception $e) {
            $this->logger($e->getMessage());
        }
    }


}