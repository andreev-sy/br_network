<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $gorko_id
 * @property int $name
 * @property int $restaurant_id
 * @property int $price
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $type
 * @property string $type_name
 */
class RoomsSpec extends \yii\db\ActiveRecord
{
    public $admin_flag = false;

    public static function tableName()
    {
        return 'pmn.rooms_restaurants_spec';
    }

    public function rules()
    {
        return [
            [['spec_id', 'room_id'], 'required'],
            [['spec_id', 'room_id', 'price'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spec_id' => 'Spec ID',
            'room_id' => 'Room ID',
            'price' => 'Price',
        ];
    }

    public function getSpec(){
        return $this->hasOne(RestaurantsSpec::className(), ['spec_id' => 'id']);
    }

    public function getRoom(){
        return $this->hasOne(Room::className(), ['room_id' => 'id']);
    }

    public static function getRoomsSpecByRoom($room_id){
        return self::find()->where(['room_id' => $room_id])->all();
    }

    public static function updateSpecPrices($room_id, $arr_spec_prices) {
        $arr_spec_room = $arr_replace = array();
        foreach (self::getRoomsSpecByRoom($room_id) as $key => $spec_room) {
           $arr_spec_room[$spec_room->spec_id] = $spec_room;
        }
        foreach ($arr_spec_prices as $spec_id => $arr_spec_price) {
            if (isset($arr_spec_room[$spec_id]) && $arr_spec_room[$spec_id]->price != $arr_spec_price) {
                $arr_spec_room[$spec_id]->price = $arr_spec_price;
                $arr_spec_room[$spec_id]->save();
            } else if (!isset($arr_spec_room[$spec_id])){
                $room_spec = new RoomsSpec();
                $room_spec->spec_id = $spec_id;
                $room_spec->room_id = $room_id;
                $room_spec->price = $arr_spec_price;
                $room_spec->save();
            }
        }
    }
}
