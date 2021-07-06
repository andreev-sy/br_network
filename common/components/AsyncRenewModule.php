<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\models\Restaurants;
use common\models\Rooms;
use common\models\Images;
use common\components\AsyncRenewImages;

class AsyncRenewModule extends BaseObject implements \yii\queue\JobInterface
{
	public  $restaurant_id,
			$main_connection_config,
		   	$site_connection_config;

	public function execute($queue) {
		$connection = new \yii\db\Connection($this->main_connection_config);
        $connection->open();
        Yii::$app->set('db', $connection);
        $restaurant = Restaurants::find()
            ->with('rooms')
            ->with('rooms.images')
            ->with('images')
            ->with('subdomen')
            ->where(['gorko_id' => $this->restaurant_id])
            ->limit(100000)
            ->one();

		$connection = new \yii\db\Connection($this->site_connection_config);
        $connection->open();
        Yii::$app->set('db', $connection);

        $log = file_get_contents('/var/www/pmnetwork/log/manual.log');
		$log = json_decode($log, true);
		$log[time()] = [$this->restaurant_id];
		$log = json_encode($log);
		file_put_contents('/var/www/pmnetwork/log/manual.log', $log);

		$model = Restaurants::find()
			->where([
				'gorko_id' => $restaurant->gorko_id
			])
			->one();  
	    if(!$model){
	    	$model = new Restaurants();
	    }

        //КОПИРОВАНИЕ ДАННЫХ
        $model->attributes = $restaurant->attributes;
        foreach ($restaurant->images as $key => $image) {
			$imgModel = Images::find()->where(['gorko_id' => $image['gorko_id']])->one();
			if(!$imgModel){
		    	$imgModel = new Images();
		    }

		    $imgModel->attributes = $image->attributes;
    		$imgModel->save();
		}
        $model->save();

        $restModel = Restaurants::find()
	        ->where([
	        	'gorko_id' => $restaurant->gorko_id
	        ])
	        ->one();
		if($restModel){

			foreach ($restaurant->rooms as $key => $room) {
				$roomModel = Rooms::find()->where(['gorko_id' => $room['gorko_id']])->one();
			    
			    if(!$roomModel){
			    	$roomModel = new Rooms();
			    }

			    $roomModel->attributes = $room->attributes;
			    foreach ($room->images as $key => $image) {
					$imgModel = Images::find()->where(['gorko_id' => $image['gorko_id']])->one();
					if(!$imgModel){
				    	$imgModel = new Images();
				    }

				    $imgModel->attributes = $image->attributes;
	        		$imgModel->save();		    
				}
        		$roomModel->save(); 	
			}
		}

        return 1;
	}
}