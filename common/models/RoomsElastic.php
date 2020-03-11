<?php
namespace common\models;

use common\models\Rooms;

class RoomsElastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return ['id','gorko_id','restaurant_id','price','capacity_reception','capacity','type','rent_only','banquet_price','bright_room','separate_entrance','type_name','name','features','cover_url'];
    }

    public static function index() {
        return 'pmn_svadbanaprirode';
    }
    
    public static function type() {
        return 'rooms';
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                '_parent' => [
                    'type' => 'restaurants'
                ],
                'properties' => [
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
        //echo '<pre>';
        //print_r(array_merge(static::mapping(), RestaurantsElastic::mapping()));
        //echo '</pre>';
        //exit;
        $command->setMapping(static::index(), static::type(), array_merge(static::mapping(), RestaurantsElastic::mapping()));
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
            'mappings' => array_merge(static::mapping(), RestaurantsElastic::mapping()),
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
        $models = Rooms::find()
            ->limit(100000)
            ->all();
        foreach ($models as $model) {
            $res = self::addRecord($model);
        }
        $models = Restaurants::find()
            ->limit(100000)
            ->all();
        foreach ($models as $model) {
            $res = RestaurantsElastic::addRecord($model);
        }
        echo 'Обновление индекса '. self::index().' '. self::type() .' завершено<br>';
    }

    public static function addRecord($model){
        $isExist = false;
        
        try{
            $record = self::get($model->id);
            if(!$record){
                $record = new self();
                $record->setPrimaryKey($model->id);
            }
            else{
                $isExist = true;
            }
        }
        catch(\Exception $e){
            $record = new self();
            $record->setPrimaryKey($model->id);
        }

        $record->id = $model->id;
        $record->gorko_id = $model->gorko_id;
        $record->restaurant_id = $model->restaurant_id;
        $record->price = $model->price;
        $record->capacity_reception = $model->capacity_reception;
        $record->capacity = $model->capacity;
        $record->type = $model->type;
        $record->rent_only = $model->rent_only;
        $record->banquet_price = $model->banquet_price;
        $record->bright_room = $model->bright_room;
        $record->separate_entrance = $model->separate_entrance;
        $record->type_name = $model->type_name;
        $record->name = $model->name;
        $record->features = $model->features;
        $record->cover_url = $model->cover_url;
        
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

    public function getRestaurants()
    {
        return $this->hasOne(RestaurantsElastic::className(), ['id' => 'restaurant_id']);
    }

    public function getFiltered(){
        self::find()
            ->query(
                [
                    "has_parent" => [
                        "parent_type" => "restaurants",
                        "query" => [
                            "term" => [
                                "district" => [
                                    "value" => 547
                                ]
                            ]
                        ],
                    ]
                ]
            )
            ->limit(10)
            ->all();;
    }
}