<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;
use Telegram\Bot\Api;
use common\models\premium\PremiumRest;
use common\models\premium\Telegram;
use common\models\Restaurants;
use common\models\Rooms;

class TelegramBot extends BaseObject{

    private function getTelegramApi(){
        $token = '6090427648:AAEiTTFtEVb5ZIwopOmDHy6k8uvppKOyWNc';
		return new Api($token);
    }

    //Первое сообщение при добавлении
    public function start($chat_id){
        $telegram_api = $this->getTelegramApi();

	    $telegram_api->sendMessage([
	        'chat_id' => $chat_id,
	        'text' => 'Введите пароль:',
	    ]);
    }

    //Ввод пароля
    public function password($message){
        $telegram_api = $this->getTelegramApi();
        $chatId = $message->getChat()->getId();
	    $userId  = $message->getFrom()->getId();
	    $password = $message->getText();

	    $connection = new \yii\db\Connection([
			'dsn' 		=> 'mysql:host=localhost;dbname=pmn_premium',
			'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
			'charset'  	=> 'utf8mb4',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);

	    $premium_rests = PremiumRest::find()
	    	->where(['telegram_pass' => $password])
	    	->with('channelInfo')
	    	->all();
	    $answer = "";

	    if(count($premium_rests)){
	    	$answer = "Получен доступ к статистике для:\n";

	    	foreach ($premium_rests as $premium_rest) {
	    		$telegram_rel = new Telegram();
	    		$telegram_rel->tg_user_id = $userId;
				$telegram_rel->tg_chat_id = $chatId;
	    		$telegram_rel->premium_rest_id = $premium_rest->id;
	    		$telegram_rel->save();
	    		$premium_rest->channel_name = $premium_rest->channelInfo->name;
	    	}

	    	$connection = new \yii\db\Connection([
				'dsn' 		=> 'mysql:host=localhost;dbname=pmn',
				'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
				'charset'  	=> 'utf8mb4',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

			$rest_list = '';
			$rest_count = 0;
			foreach ($premium_rests as $premium_rest) {
				$restaurant = Restaurants::find()
					->where(['gorko_id' => $premium_rest->gorko_id])
					->one();

				if($restaurant){
					$rest_count++;
					$answer .= "'" . $restaurant->name. "'" . " на сайте " . $premium_rest->channel_name . "\n";
					$rest_list .= $rest_count > 1 ? ', '.$premium_rest->channel_name : $premium_rest->channel_name;
 				}
			}

			if($rest_count > 1){
				$answer .= "\n".'Этот бот будет отправлять данные о звонках в ваш ресторан с сайтов '.$rest_list. "\n";
			}
			else{
				$answer .= "\n".'Этот бот будет отправлять данные о звонках в ваш ресторан с сайта '.$rest_list. "\n";
			}
			

			$answer .= "\n".'Доступные команды:
/stat - вся статистика по размещению
/stat7 - статистика по размещению за последнюю неделю
/stat30 - статистика по размещению за последний месяц
/stat365 - статистика по размещению за последний год';
	    }
	    else{
	    	$answer = "Неправильный пароль";
	    }

	    $telegram_api->sendMessage([
	        'chat_id' => $chatId,
	        'text' => $answer,
	    ]);
    }

    //Вывод доступных команд
    public function commandList($chat_id){
        $telegram_api = $this->getTelegramApi();

        $telegram_api->sendMessage([
	        'chat_id' => $chat_id,
	        'text' => "Доступные команды:
			
	/stat - вся статистика по размещению
	/stat7 - статистика по размещению за последнюю неделю
	/stat30 - статистика по размещению за последний месяц
	/stat365 - статистика по размещению за последний год",
	    ]);
    }

    //Вывод статистики
    public function stat($message, $user_rests, $period){
        $telegram_api = $this->getTelegramApi();
        $chatId = $message->getChat()->getId();

	    $answer = "";

		if($period){
			$answer .= "Статистика за последние ".$period." дней";
		}
		else{
			$answer .= "Статистика за всё время";
		}

		foreach($user_rests as $user_rest){

			$connection = new \yii\db\Connection([
				'dsn' 		=> 'mysql:host=localhost;dbname=pmn_premium',
				'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
				'charset'  	=> 'utf8mb4',
			]);
			$connection->open();
			Yii::$app->set('db', $connection);

			$premium_rest = PremiumRest::find()
		    	->where(['id' => $user_rest->premium_rest_id])
		    	->with('channelInfo')
		    	->one();
			$premium_rest->channel_name = $premium_rest->channelInfo->name;

			$unique_user 	= $premium_rest->getUniqueUsers($period);
			$phone_clicks 	= $premium_rest->getPhoneClicks($period);

			if($unique_user || $phone_clicks){
				$connection = new \yii\db\Connection([
					'dsn' 		=> 'mysql:host=localhost;dbname=pmn',
					'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
					'charset'  	=> 'utf8mb4',
				]);
				$connection->open();
				Yii::$app->set('db', $connection);

				$restaurant = Restaurants::find()
					->where(['gorko_id' => $premium_rest->gorko_id])
					->one();
				
				$answer .= "\n\n'" . $restaurant->name. "'" . " на сайте " . $premium_rest->channel_name . "\n";
			}
				
				
			if($unique_user) 
				$answer .= "Количество уникальных пользователей - <b>".$unique_user."</b>\n";
			if($phone_clicks) 
				$answer .= "Количество кликов по кнопке 'Показать номер' - <b>".$phone_clicks."</b>\n";
		}

		if($answer == "")
			$answer = "Нет статистики за заданный период";


		$telegram_api->sendMessage([
			'chat_id' => $chatId,
			'text' => $answer,
			'parse_mode' => 'HTML'
		]);
    }

    //Отправка информации о звонке
    public function sendCallInfo($call){
        $telegram_api = $this->getTelegramApi();

		$connection = new \yii\db\Connection([
			'dsn' 		=> 'mysql:host=localhost;dbname=pmn_premium',
			'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
			'charset'  	=> 'utf8mb4',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);

        $rest = PremiumRest::find()
			->where([
				'gorko_id' => $call->restaurant_id,
				'channel' => $call->channel_id,
			])
			->one();
		$tg_users = Telegram::find()
			->where(['premium_rest_id' => $rest->id])
			->all();

		if($tg_users){
			foreach($tg_users as $tg_user){
				$chatId = $tg_user->tg_chat_id;

				$diff = strtotime($call->dt_hangup) - strtotime($call->dt_answer);
				$answer = "Поступил звонок:
	Номер телефона: <b>".$call->caller_phone."</b>";
				if($call->dt_answer){
					$answer .= "\nДата и время начала звонка: <b>".$call->dt_answer."</b>
					Длительность звонка: <b>".$diff." сек.</b>";
				}
				else{
					return 1;
				}

				$telegram_api->sendMessage([
					'chat_id' => $chatId,
					'text' => $answer,
					'parse_mode' => 'HTML'
				]);
			}
		}
    }

	//Отправка формы на сайте
	public function roomCallback($payload, $channel, $room_id){
        $telegram_api = $this->getTelegramApi();

		$connection = new \yii\db\Connection([
			'dsn' 		=> 'mysql:host=localhost;dbname=pmn_premium',
			'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
			'charset'  	=> 'utf8mb4',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);

		$rest = PremiumRest::find()
			->where([
				'gorko_id' => $payload['venue_id'],
				'channel' => $channel,
			])
			->with('channelInfo')
		    ->one();
		
		$rest->channel_name = $rest->channelInfo->name;
		$rest->channel_mail = $rest->channelInfo->email;
		$rest->channel_desc = $rest->channelInfo->email_desc;

		$tg_users = Telegram::find()
			->where(['premium_rest_id' => $rest->id])
			->all();
			
		$connection = new \yii\db\Connection([
			'dsn' 		=> 'mysql:host=localhost;dbname=pmn',
			'username' => 'pmnetwork',
				'password' => 'P6L19tiZhPtfgseN',
			'charset'  	=> 'utf8mb4',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);

		$room = Rooms::find()
			->where(['gorko_id' => $room_id])
			->one();
		
		$answer = 'Заявка на банкет в зале '.$room->name.' с сайта '.$rest->channel_name."\n\n";
		if(isset($payload['name']))			$answer .= 	'Имя: '.			$payload['name']."\n";
		if(isset($payload['phone']))		$answer .= 	'Телефон: '.		$payload['phone']."\n";
		if(isset($payload['event_type']))	$answer .= 	'Тип события: '.	$payload['event_type']."\n";
		if(isset($payload['guests']))		$answer .= 	'Число гостей: '.	$payload['guests']."\n";
		if(isset($payload['date']))			$answer .= 	'Желаемая дата: '.	$payload['date']."\n";	
		if(isset($payload['coment_text']))	$answer .= 	'Комментарий: '.	$payload['coment_text']."\n";
		
		$answer_mail = str_replace("\n", '<br/>', $answer);


		if($tg_users){
			foreach($tg_users as $tg_user){
				$chatId = $tg_user->tg_chat_id;
				
				$telegram_api->sendMessage([
					'chat_id' => $chatId,
					'text' => $answer,
					'parse_mode' => 'HTML'
				]);
			}
		}

		$this->sendMail($rest->email, 'Заявка с сайта '.$rest->channel_name, $rest->channel_mail, $rest->channel_desc, $answer_mail);

		return 1;
	}

	private function sendMail($to, $subj, $from, $from_desc, $answer)
	{
		if($to){
			$message = Yii::$app->mailer->compose()
				->setFrom([$from => $from_desc])
				->setTo($to)
				->setSubject($subj)
				->setCharset('utf-8')
				//->setTextBody('Plain text content')
				->setHtmlBody($answer);
			$message->send();
		}
		return 1;
	}

}