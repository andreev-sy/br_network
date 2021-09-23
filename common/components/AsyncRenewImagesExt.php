<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\ImagesExt;
use yii\helpers\ArrayHelper;

class AsyncRenewImagesExt extends BaseObject implements \yii\queue\JobInterface
{
	public  $gorko_id,
		   	$connection_config;

	public function execute($queue) {
		$connection = new \yii\db\Connection($this->connection_config);
		$connection->open();
		Yii::$app->set('db', $connection);
		$imgModel = ImagesExt::find()->where(['gorko_id' => $this->gorko_id])->one($connection);

		//ТЕКУЩИЕ КАРТИНКИ
		$images_cur = ImagesExt::find()
			->where(['rest_id' => $this->gorko_id])
			->asArray()
			->all();
		$images_cur = ArrayHelper::index($images_cur, 'gorko_id');

		//КАРТИНКИ ИЗ API
		$api_url = 'https://api.gorko.ru/api/v3/satellitevenuemedia/'.$this->gorko_id;
		$ch_images = curl_init();
		curl_setopt($ch_images, CURLOPT_URL, $api_url);
		curl_setopt($ch_images, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch_images, CURLOPT_ENCODING, '');	    
		$images_api = (json_decode(curl_exec($ch_images), true))['entity'][$this->gorko_id]['specMedia'];
		curl_close($ch_images);


		foreach ($images_api as $event_key => $images_api_arr) {
			foreach ($images_api_arr['room'] as $room_id => $room_api_arr) {
				foreach ($room_api_arr as $image_sort => $image_api) {
					$image_cur = ImagesExt::find()
						->where(['gorko_id' => $image_api['id']])
						->one();
					if($image_cur){
						if($image_cur->sort != $image_sort){
							$image_cur->sort = $image_sort;
							$image_cur->save();
						}
						unset($images_cur[$image_api['id']]);
					}
					else{
						$new_image = new ImagesExt();
						$new_image->gorko_id 	= $image_api['id'];
						$new_image->path 		= $image_api['url'];
						$new_image->sort 		= $image_sort;
						$new_image->event_id 	= $event_key;
						$new_image->timestamp 	= time();
						$new_image->room_id 	= $room_id;
						$new_image->rest_id 	= $this->gorko_id;
						$new_image->save();
					}
				}
			}
		}

		foreach ($images_cur as $gorko_id => $image_cur) {
			$image_del = ImagesExt::findOne($image_cur->id);
			$image_del->delete();
		}
	}
}