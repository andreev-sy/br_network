<?php

namespace common\models;

use Yii;
use common\models\siteobject\BaseSiteObject;
use common\models\Rooms;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $name
 * @property int $restaurant_id
 */
class RoomsModule extends BaseSiteObject
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rooms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'restaurant_id'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
                $room = Rooms::find()
                    ->where(['gorko_id' => $this->id])
                    ->one();

                Yii::$app->set('db', $current_connection);
                if($room){
                    $this->name = $room->name;
                    $this->restaurant_id = $room->restaurant_id;
                }                    
            }
            return true;
        }
        return false;

    }
}