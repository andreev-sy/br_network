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
            [['spec_id', 'room_id', 'gorko_id', 'price'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spec_id' => 'Spec ID',
            'room_id' => 'Room ID',
            'room_id' => 'Gorko ID',
            'price' => 'Price',
        ];
    }

    public function getSpec(){
      //   return $this->hasOne(RestaurantsSpec::className(), ['spec_id' => 'id']);
        return $this->hasOne(RestaurantsSpec::className(), ['id' => 'spec_id']);
    }

    public function getRoom(){
        return $this->hasOne(Room::className(), ['room_id' => 'id']);
    }

    public static function getRoomsSpecByRoom($room_id){
        return self::find()->where(['room_id' => $room_id])->all();
    }

    public static function checkRoomSpecsByRoom($room_id, $spec_id){
        $isExist = false;
        foreach (self::getRoomsSpecByRoom($room_id) as $spec_room) {
            if($spec_room->spec_id == $spec_id) {
                $isExist = true;
                break;
            }
        }
        return $isExist;
    }

    public static function getSpecPriceByRoom($room_id, $spec_id){
        $spec_room = self::find()->where(['room_id' => $room_id])->andWhere(['spec_id' => $spec_id])->one();
        return ($spec_room ? $spec_room->price : false);
    }

    public static function getSpecPriceForRest($room_ids, $spec_id){
        $arr_prices = array();
        $spec_rooms = self::find()->where(['spec_id' => $spec_id])->andWhere(['IN','room_id', $room_ids])->all();
        foreach ($spec_rooms as $key => $spec_room) {
            array_push($arr_prices, $spec_room->price);
        }
        return !empty($arr_prices) ? min($arr_prices) : false;
    }

    public static function getSpecsForRest($room_ids){
        $arr_specs = array();
        $spec_rooms = self::find()->where(['IN','gorko_id', $room_ids])->all();
        foreach ($spec_rooms as $spec_room) {
            $arr_specs[$spec_room->gorko_id][$spec_room->spec_id] = $spec_room->price;
        }
        return $arr_specs;
    }

    public static function updateSpecPrices($room_id, $gorko_id, $arr_spec_prices) {
        $arr_spec_room = $arr_replace = array();
        foreach (self::getRoomsSpecByRoom($room_id) as $key => $spec_room) {
           $arr_spec_room[$spec_room->spec_id] = $spec_room;
        }

        foreach ($arr_spec_prices as $spec_id => $arr_spec_price) {
            if (isset($arr_spec_room[$spec_id])){
                if ($arr_spec_room[$spec_id]->price != $arr_spec_price) {
                    $arr_spec_room[$spec_id]->price = $arr_spec_price;
                    $arr_spec_room[$spec_id]->save();
                }
                unset($arr_spec_room[$spec_id]);
            } else {
                $room_spec = new RoomsSpec();
                $room_spec->spec_id = $spec_id;
                $room_spec->room_id = $room_id;
                $room_spec->gorko_id = $gorko_id;
                $room_spec->price = $arr_spec_price;
                $room_spec->save();
            }
        }

        if (!empty($arr_spec_room)) {
            foreach ($arr_spec_room as $spec_id => $spec_room) {
                //$specRoom = self::find()->where(['room_id' => $room_id, 'spec_id' => $spec_id])->one();
                $spec_room->delete();
            }
        }
    }
}
