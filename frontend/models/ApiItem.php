<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;
use frontend\components\AsyncDownloads;

class ApiItem extends Model
{
	public function getData($id) {
		//echo $id;
		//exit;

		if( $curl = curl_init() ) {
		    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/restaurants/'.$id.'?embed=rooms&fields=address,params,covers,district&preview_size=0x200x0,266x200x1');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    $response = json_decode(curl_exec($curl), true);
		    curl_close($curl);
	  	}

		$item = $response['restaurant'];
		$item['covers'] = [];
		//$item['rooms'] = [];
		$item['rooms'][0]['media'] = [];

		echo '<pre>';
		print_r($item['rooms'][0]);
		echo '</pre>';
		exit;

		
		foreach ($item['covers'] as $key => $value) {
			$base_url = '/var/www/pmnetwork/pmnetwork/frontend/web/uploads/itemsCovers/';
			$cover_url = str_replace("=s0", "", $value['original_url']).'=w709-h472';
			$item_id = $item['id'];
			$cover_name = basename($cover_url).'.jpg';
			$cache_path = $base_url.$item_id.'/';
			if (!is_dir($cache_path)) {
				mkdir($cache_path, 0755, true);
			}

			if($key == 0){				
				if(!file_exists($cache_path.$cover_name)){
					$size_origin = getimagesize($cover_url);
					$size_watermark = getimagesize('/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png');
					$start_point = [$size_origin[0] - $size_watermark[0] - 20, $size_origin[1] - $size_watermark[1] - 10];
					$image = Image::watermark($cover_url, '/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png', $start_point)->save($cache_path.$cover_name, ['quality' => 90]);
				}
				$item['cover_urls'][] = '/uploads/itemsCovers/'.$item_id.'/'.$cover_name;
			}
			else{
				if(file_exists($cache_path.$cover_name)){
					$item['cover_urls'][] = '/uploads/itemsCovers/'.$item_id.'/'.$cover_name;
				}
				else{
					//print_r($cover_url.'<br/>'.$cache_path.$cover_name.'<br/>');
					$queue_id = Yii::$app->queue->push(new AsyncDownloads([
					    'url' => $cover_url,
					    'file' => $cache_path.$cover_name,
					]));

					$item['cover_urls'][] = [
						'id' => $queue_id,
						'url' => '/uploads/itemsCovers/'.$item_id.'/'.$cover_name,
					];
				}
			}
		}		
		//exit;

		return $item;
	}
}