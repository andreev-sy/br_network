<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\imagine\Image;

class AjaxController extends Controller
{
	public $enableCsrfValidation = false;

	public function actionWatermark()
	{
		$base_url = '/var/www/pmnetwork/pmnetwork/frontend/web/uploads/itemsCovers/';
		$cover_url = str_replace("=w138-h92", "", $_POST['url']).'=w717-h472';
		$item_id = $_POST['id'];
		$cover_name = basename($cover_url).'.jpg';
		$cache_path = $base_url.$item_id.'/';

		if (!is_dir($cache_path)) {
			mkdir($cache_path, 0755, true);
		}
		if(!file_exists($cache_path.$cover_name)){
			$size_origin = getimagesize($cover_url);
			$size_watermark = getimagesize('/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png');
			$start_point = [$size_origin[0] - $size_watermark[0] - 20, $size_origin[1] - $size_watermark[1] - 10];
			$image = Image::watermark($cover_url, '/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png', $start_point)->save($cache_path.$cover_name, ['quality' => 90]);
		}	

		$response = '/uploads/itemsCovers/'.$item_id.'/'.$cover_name;

		return $response;
	}

	public function actionWatermarkAsync()
	{
		$id = $_POST['id'];
		while (!Yii::$app->queue->isDone($id)) {
			usleep(100000);
		}

		return 'success';
	}
}