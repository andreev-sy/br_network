<?php

namespace common\models;

use Yii;
use common\models\siteobject\BaseSiteObject;
use common\models\Restaurants;
use frontend\modules\pmnbd\models\ElasticItems;

/**
 * This is the model class for table "restaurants".
 *
 * @property int $id
 * @property int $gorko_id
 * @property string $name
 * @property string $address
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $price
 * @property string $cover_url
 */
class RestaurantsModule extends BaseSiteObject
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'active'], 'integer'],
            [['name', 'address', 'custom_slug', 'images_inactive'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Gorko ID',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $current_connection = Yii::$app->get('db');
                $connection = new \yii\db\Connection([
                    'dsn'       => 'mysql:host=localhost;dbname=pmn',
                    'username' => 'pmnetwork',
                    'password' => 'P6L19tiZhPtfgseN',
                    'charset' => 'utf8mb4',
                ]);
                $connection->open();
                Yii::$app->set('db', $connection);
                $restaurant = Restaurants::find()
                    ->where(['gorko_id' => $this->id])
                    ->one();

                Yii::$app->set('db', $current_connection);
                if($restaurant){
                    $this->name = $restaurant->name;
                    $this->address = $restaurant->address;
                }                    
            }
            return true;
        }
        return false;

    }

    //public function getRestaurant()
    //{
    //    
    //    $restaurant = $this->hasOne(Restaurants::className(), ['gorko_id' => 'id']);
    //    $connection = new \yii\db\Connection([
    //        'username' => 'root',
    //        'password' => 'GxU25UseYmeVcsn5Xhzy',
    //        'charset'  => 'utf8mb4',
    //        'dsn'      => 'mysql:host=localhost;dbname=pmn_bd',
    //    ]);
    //    $connection->open();
    //    Yii::$app->set('db', $connection);
    //    return $restaurant;
    //}
}
