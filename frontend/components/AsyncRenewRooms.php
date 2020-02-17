<?php
namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use backend\models\Restaurants;

class AsyncRenewRooms extends BaseObject implements \yii\queue\JobInterface
{
	public $gorko_id;

	public function execute($queue) {
		//echo '<pre>'.$this->$gorko_id.'</pre>';

		if( $curl = curl_init() ) {
		    curl_setopt($curl, CURLOPT_URL, 'https://api.gorko.ru/api/v2/restaurants/'.$this->gorko_id.'?embed=rooms&fields=address,params,covers,district');
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_ENCODING, '');
		    $response = json_decode(curl_exec($curl), true)['restaurant'];
		    curl_close($curl);

		    $model = Restaurants::find()->where(['gorko_id' => $this->gorko_id])->one();
		    
		    if(!$model){
		    	$model = new Restaurants;
		    }

		    $attributes = [];
		    $attributes['gorko_id'] = $response['id'];
		    $attributes['name'] = $response['name'];
			$attributes['address'] = $response['address'];
			if($response['covers'][0]){
				$attributes['cover_url'] = str_replace("=s0", "", $response['covers'][0]['original_url']);
			}			

		    $model->attributes = $attributes;
		    $model->save();
	  	}
	}
}