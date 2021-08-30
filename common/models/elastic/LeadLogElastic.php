<?php
namespace common\models\elastic;

use Yii;
use yii\helpers\ArrayHelper;

class LeadLogElastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return [
            'source',
            'payload',
            'raw_payload',
            'response',
            'info',
            'timestamp',
            'code',
            'name',
            'phone',
            'city_id',
            'date',
            'api_alias',
            'attempts',
            'lead_id',
            'status'
        ];
    }

    public static function index() {
        return 'pmn_lead_log';
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
                    'source'        => ['type' => 'text'],
                    'payload'       => ['type' => 'text'],
                    'raw_payload'   => ['type' => 'text'],
                    'response'      => ['type' => 'text'],
                    'info'          => ['type' => 'text'],
                    'timestamp'     => ['type' => 'integer'],
                    'code'          => ['type' => 'text'],
                    'name'          => ['type' => 'text'],
                    'phone'         => ['type' => 'text'],
                    'city_id'       => ['type' => 'text'],
                    'date'          => ['type' => 'text'],
                    'api_alias'     => ['type' => 'text'],
                    'attempts'      => ['type' => 'integer'],
                    'lead_id'       => ['type' => 'integer'],
                    'status'        => ['type' => 'text'],
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

        $record->source         = $log_arr['source'];
        $record->payload        = $log_arr['payload'];
        $record->raw_payload    = $log_arr['raw_payload'];
        $record->response       = $log_arr['response'];
        $record->timestamp      = $log_arr['timestamp'];
        $record->code           = $log_arr['code'];
        $record->name           = $log_arr['name'];
        $record->phone          = $log_arr['phone'];
        $record->city_id        = $log_arr['city_id'];
        $record->api_alias      = $log_arr['api_alias'];
        $record->attempts       = $log_arr['attempts'];
        $record->date           = $log_arr['date'];
        $record->lead_id        = $log_arr['lead_id'];
        $record->status         = $log_arr['status'];
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

    public static function updateStatus($log_arr){
        $record = self::find()
            ->query(['bool' => ['must' => ['match'=>['lead_id' => $log_arr['lead_id']]]]])
            ->limit(1)
            ->one();
        if(!$record){
            return 'Нет записи с таким lead_id';
        }
        $record->status = $log_arr['status'];
        $result = $record->update();
        return $result;
    }
}