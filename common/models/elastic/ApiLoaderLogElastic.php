<?php
namespace common\models\elastic;

use Yii;
use yii\helpers\ArrayHelper;

class ApiLoaderLogElastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return [
            'rest_id',
            'status',
            'date'
        ];
    }

    public static function index() {
        return 'pmn_api_log';
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
                    'rest_id'       => ['type' => 'integer'],
                    'status'        => ['type' => 'text'],
                    'date'          => ['type' => 'text'],
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
        //$res = self::updateIndex($main_connection_config, $site_connection_config);
    }

    public static function updateIndex($log_arr) {
        $res = self::addRecord($log_arr);
        echo 'Обновление индекса '. self::index().' '. self::type() .' завершено'."\n";
    }

    public static function addRecord($log_arr){
        $record = new self();

        $record->rest_id        = $log_arr['rest_id'];
        $record->status         = $log_arr['status'];
        $record->date           = $log_arr['date'];
        $record->setPrimaryKey(microtime());
        
        try{
            $result = $record->insert();
        }
        catch(\Exception $e){
            $result = $e;
        }
        
        //print_r($result);
        //print_r("\n");

        return $result;
    }
}