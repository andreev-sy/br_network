<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\Images;

class AsyncRenewImages extends BaseObject implements \yii\queue\JobInterface
{
	public  $item_id,
		   	$type,
		   	$dsn;

	public function execute($queue) {
		$connection = new \yii\db\Connection([
		    'dsn' => $this->dsn,
		    'username' => 'root',
		    'password' => 'LP_db_',
		    'charset' => 'utf8',
		]);
		$connection->open();
		Yii::$app->set('db', $connection);

		$imgModel = Images::find()->where(['item_id' => $this->item_id, 'type' => $this->type])->all($connection);

		foreach ($imgModel as $img) {
			$curl = curl_init();
			$file = '/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png';
			$mime = mime_content_type($file);
			$info = pathinfo($file);
			$name = $info['basename'];
			$output = curl_file_create($file, $mime, $name);
			$params = [
				'mediaId' => $img->gorko_id,
				'token'=> '4aD9u94jvXsxpDYzjQz0NFMCpvrFQJ1k',
				'watermark' => $output,
				'hash_key' => 'svadbanaprirode'
			];
			curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/tools/mediaToSatellite');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

		    $response = curl_exec($curl);
		    $response_obj = json_decode($response);
		    print_r(json_decode($response));
		    curl_close($curl);

		    $img->subpath = $response_obj->url;
		    $img->waterpath = $response_obj->url_watermark;
		    $img->timestamp = time();

		    $img->save();		    
		}
	}
}