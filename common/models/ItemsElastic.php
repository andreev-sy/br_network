<?php
namespace common\models;

use common\models\Restaurants;

class ItemsElastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return ['restaurant_id', 'restaurant_gorko_id', 'restaurant_price', 'restaurant_min_capacity', 'restaurant_max_capacity', 'restaurant_district', 'restaurant_parent_district', 'restaurant_alcohol', 'restaurant_firework', 'restaurant_name', 'restaurant_address', 'restaurant_cover_url', 'restaurant_latitude', 'restaurant_longitude', 'restaurant_own_alcohol', 'restaurant_cuisine', 'restaurant_parking', 'restaurant_extra_services', 'restaurant_payment', 'restaurant_special', 'restaurant_phone', 'id', 'gorko_id', 'restaurant_id', 'price', 'capacity_reception', 'capacity', 'type', 'rent_only', 'banquet_price', 'bright_room', 'separate_entrance', 'type_name', 'name', 'features', 'cover_url'];
    }

    public static function index() {
        return 'pmn_svadbanaprirode';
    }
    
    public static function type() {
        return 'items';
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'restaurant_id'                    => ['type' => 'integer'],
                    'restaurant_gorko_id'              => ['type' => 'integer'],
                    'restaurant_price'                 => ['type' => 'integer'],
                    'restaurant_min_capacity'          => ['type' => 'integer'],
                    'restaurant_max_capacity'          => ['type' => 'integer'],
                    'restaurant_district'              => ['type' => 'integer'],
                    'restaurant_parent_district'       => ['type' => 'integer'],
                    'restaurant_alcohol'               => ['type' => 'integer'],
                    'restaurant_firework'              => ['type' => 'integer'],
                    'restaurant_name'                  => ['type' => 'text'],
                    'restaurant_address'               => ['type' => 'text'],
                    'restaurant_cover_url'             => ['type' => 'text'],
                    'restaurant_latitude'              => ['type' => 'text'],
                    'restaurant_longitude'             => ['type' => 'text'],
                    'restaurant_own_alcohol'           => ['type' => 'text'],
                    'restaurant_cuisine'               => ['type' => 'text'],
                    'restaurant_parking'               => ['type' => 'text'],
                    'restaurant_extra_services'        => ['type' => 'text'],
                    'restaurant_payment'               => ['type' => 'text'],
                    'restaurant_special'               => ['type' => 'text'],
                    'restaurant_phone'                 => ['type' => 'text'],
                    'id'                    => ['type' => 'integer'],
                    'gorko_id'              => ['type' => 'integer'],
                    'restaurant_id'         => ['type' => 'integer'],
                    'price'                 => ['type' => 'integer'],
                    'capacity_reception'    => ['type' => 'integer'],
                    'capacity'              => ['type' => 'integer'],
                    'type'                  => ['type' => 'integer'],
                    'rent_only'             => ['type' => 'integer'],
                    'banquet_price'         => ['type' => 'integer'],
                    'bright_room'           => ['type' => 'integer'],
                    'separate_entrance'     => ['type' => 'integer'],
                    'type_name'             => ['type' => 'text'],
                    'name'                  => ['type' => 'text'],
                    'features'              => ['type' => 'text'],
                    'cover_url'             => ['type' => 'text'],
                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            'settings' => [
                'number_of_replicas' => 0,
                'number_of_shards' => 1,
            ],
            'mappings' => static::mapping(),
            //'warmers' => [ /* ... */ ],
            //'aliases' => [ /* ... */ ],
            //'creation_date' => '...'
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }

    public static function refreshIndex() {
        $res = self::deleteIndex();
        $res = self::updateMapping();
        $res = self::createIndex();
        $restaurants = Restaurants::find()
            ->with('rooms')
            ->limit(100000)
            ->all();
        foreach ($restaurants as $restaurant) {
            foreach ($restaurant->rooms as $room) {
                $res = self::addRecord($room, $restaurant);
            }            
        }
        echo 'Обновление индекса '. self::index().' '. self::type() .' завершено<br>';
    }

    public static function addRecord($room, $restaurant){
        $isExist = false;
        
        try{
            $record = self::get($room->id);
            if(!$record){
                $record = new self();
                $record->setPrimaryKey($room->id);
            }
            else{
                $isExist = true;
            }
        }
        catch(\Exception $e){
            $record = new self();
            $record->setPrimaryKey($room->id);
        }

        $record->id  = $model->id;
        
        $record->restaurant_id = $restaurant->id;
        $record->restaurant_gorko_id = $restaurant->gorko_id;
        $record->restaurant_price = $restaurant->price;
        $record->restaurant_min_capacity = $restaurant->min_capacity;
        $record->restaurant_max_capacity = $restaurant->max_capacity;
        $record->restaurant_district = $restaurant->district;
        $record->restaurant_parent_district = $restaurant->parent_district;
        $record->restaurant_alcohol = $restaurant->alcohol;
        $record->restaurant_firework = $restaurant->firework;
        $record->restaurant_name = $restaurant->name;
        $record->restaurant_address = $restaurant->address;
        $record->restaurant_cover_url = $restaurant->cover_url;
        $record->restaurant_latitude = $restaurant->latitude;
        $record->restaurant_longitude = $restaurant->longitude;
        $record->restaurant_own_alcohol = $restaurant->own_alcohol;
        $record->restaurant_cuisine = $restaurant->cuisine;
        $record->restaurant_parking = $restaurant->parking;
        $record->restaurant_extra_services = $restaurant->extra_services;
        $record->restaurant_payment = $restaurant->payment;
        $record->restaurant_special = $restaurant->special;
        $record->restaurant_phone = $restaurant->phone;

        $record->id = $room->id;
        $record->gorko_id = $room->gorko_id;
        $record->restaurant_id = $room->restaurant_id;
        $record->price = $room->price;
        $record->capacity_reception = $room->capacity_reception;
        $record->capacity = $room->capacity;
        $record->type = $room->type;
        $record->rent_only = $room->rent_only;
        $record->banquet_price = $room->banquet_price;
        $record->bright_room = $room->bright_room;
        $record->separate_entrance = $room->separate_entrance;
        $record->type_name = $room->type_name;
        $record->name = $room->name;
        $record->features = $room->features;
        $record->cover_url = $room->cover_url;

        
        try{
            if(!$isExist){
                $result = $record->insert();
            }
            else{
                $result = $record->update();
            }
        }
        catch(\Exception $e){
            $result = false;
        }
        
        return $result;
    }
}