<?php
namespace common\components;

use Yii;
use yii\base\BaseObject;
use common\components\AsyncRenewRestaurants;
use common\models\Restaurants;

class RenewPremiumRest extends BaseObject
{    
    public static function renew_rest($params)
    {
        $connection = new \yii\db\Connection($params['main_connection_config']);
		$connection->open();
		Yii::$app->set('db', $connection);

        $renew_mysql = new AsyncRenewRestaurants([
            'connection_config' => $params['main_connection_config'],
            'gorko_id' 	=> $params['gorko_id']
        ]);
        $renew_mysql->premium_rest();
        echo 'MySQL ресторана обновлён'."\n";

        
		$restaurant = Restaurants::find()
            ->where(['gorko_id' => $params['gorko_id']])
            ->one();
        $renew_specs = new AsyncRenewSpecs([
            'connection_config' => $params['main_connection_config'],
            'gorko_id' 	=> $restaurant->gorko_id,
            'restaurant' 	=> $restaurant
        ]);
        $renew_specs->premium_rest();
        echo 'MySQL специализация залов и цены обновлёны'."\n";

        $params['elasticModel']::updateIndex($params);

        return 1;
    }
}
