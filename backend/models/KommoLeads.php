<?php

namespace backend\models;

use Yii;
use common\components\KommoCrmAPI;

/**
 * This is the model class for table "kommo_leads".
 *
 * @property int $lead_id Id лида в коммо
 * @property string|null $labor_cost Стоимость труда
 * @property string|null $response_time Время ответа
 * @property int|null $response_time_id Время ответа в диопозоне
 * @property int|null $contact_id ID контакта
 * @property int|null $message_at ID контакта
 * @property string|null $message_id ID сообщения
 * @property int $is_night Ночной
 * @property int|null $status_id Текущий статус
 * @property int|null $rejection_id Статус отказа
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class KommoLeads extends \yii\db\ActiveRecord
{

    const STATUS_IDS = [
        'ВХОДЯЩИЙ ЛИД' => 66994792, // Etapa de leads de entrada
        'НОВЫЙ ЛИД' => 66994796, // NOVO LEADS
        'ПРОВЕРЕННЫЙ ЛИД' => 66994800, // LEAD VERIFICADO
        'ПОДБОРКА ОТПРАВЛЕНА' => 66994804, // SOLICITAÇÃO ENVIADA
        'КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ' => 66994892, // O CLIENTE ESCOLHEU UM ESPAÇO DA NOSSA COMPILAÇÃO
        'ВСТРЕЧА ПРОВЕДЕНА' => 66994896, // REUNIÃO FOI AGENDADA
        'АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ' => 66994900, // ALUGADO UM ESPAÇO DA NOSSA SELEÇÃO
        'МЕРОПРИЯТИЕ ПРОВЕДЕНО' => 66994904, // O evento foi realizado
        'ОПЛАТА ПОЛУЧЕНА' => 67572748, // Pagamento recebido
        'СТАТУС ВОРОНКИ ОТМЕНЫ' => 66992304, // Сancelamento condicional
    ];

    const REJECTION_IDS = [
        'НОВЫЙ ЛИД' => 1279045, // NOVO LEADS
        'ПРОВЕРЕННЫЙ ЛИД' => 1279047, // LEAD VERIFICADO
        'ПОДБОРКА ОТПРАВЛЕНА' => 1279049, // SOLICITAÇÃO ENVIADA
        'КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ' => 1279051, // O CLIENTE ESCOLHEU UM ESPAÇO DA NOSSA COMPILAÇÃO
        'ВСТРЕЧА ПРОВЕДЕНА' => 1279053, // REUNIÃO FOI AGENDADA
        'АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ' => 1279055, // ALUGADO UM ESPAÇO DA NOSSA SELEÇÃO
        'МЕРОПРИЯТИЕ ПРОВЕДЕНО' => 1279057, // O evento foi realizado
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kommo_leads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lead_id'], 'required'],
            [['lead_id', 'response_time_id', 'is_night', 'status_id', 'rejection_id', 'contact_id'], 'integer'],
            [['labor_cost', 'response_time', 'created_at', 'updated_at', 'message_at'], 'integer'],
            [['message_id'], 'string'],
            [['lead_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lead_id' => Yii::t('app', 'ID лида в коммо'),
            'labor_cost' => Yii::t('app', 'Стоимость труда'),
            'response_time' => Yii::t('app', 'Время ответа'),
            'response_time_id' => Yii::t('app', 'Время ответа в диопозоне'),
            'contact_id' => Yii::t('app', 'ID контакта'),
            'message_at' => Yii::t('app', 'Дата сообщения'),
            'message_id' => Yii::t('app', 'ID сообщения'),
            'is_night' => Yii::t('app', 'Ночной'),
            'status_id' => Yii::t('app', 'Текущий статус'),
            'rejection_id' => Yii::t('app', 'Статус отказа'),
            'filter_year' => Yii::t('app', 'Год'),
            'filter_month' => Yii::t('app', 'Месяц'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    public static function refreshStat()
    {
        $kommo = new KommoCrmAPI(Yii::$app->params['kommo_api_config']['leads_stat']);
        $leads_m = self::find()->indexBy('lead_id')->all();

        $page = 1;
        do { 
            $params = [
                'page' => $page,
                'with' => [ 'contacts' ],
                'filter'=>[
                    'pipeline_id' => [
                        8478916, // Сancelamento condicional
                        8479348, // Leads 24
                    ]
                ]
            ];
            $response = $kommo->request('get', '/api/v4/leads', $params);
            if(empty($response['_embedded']['leads'])) break;

            foreach($response['_embedded']['leads'] as $lead){
                $lead_m = !empty($leads_m[$lead['id']]) ? $leads_m[$lead['id']] : new self();

                // достаем время ответа
                if(empty($lead_m->response_time) and isset($lead['_embedded']['contacts'][0]['id'])){
                    $lead_m->contact_id = $lead['_embedded']['contacts'][0]['id'];
                    [$first_message_time, $message_id] = self::getResponseTimeStat($lead['_embedded']['contacts'][0]['id']);
                    if($first_message_time > 0){
                        $lead_m->message_id = $message_id;
                        $lead_m->message_at = $first_message_time;
                        $lead_m->response_time = $first_message_time - $lead['created_at'];
                    }
                }
            
                // достаем отказ
                if(empty($lead_m->rejection_id)){
                    foreach($lead['custom_fields_values'] as $field){
                        if($field['field_id'] == 1011425 and !empty($field['values'][0]['enum_id'])) // Estado de recusa
                            $lead_m->rejection_id = $field['values'][0]['enum_id'];
                    }
                }

                $lead_m->lead_id = $lead['id'];
                $lead_m->labor_cost = !empty($lead['labor_cost']) ? $lead['labor_cost'] : null;
                $lead_m->status_id = $lead['status_id'];
                $lead_m->created_at = empty($lead_m->created_at) ? $lead['created_at'] : $lead_m->created_at;
                $lead_m->updated_at = $lead['updated_at'];
                $lead_m->save();
            }

            if(empty($leads['_links']['next']['href'])) break;

            $page++;
        } while (!empty($leads['_links']['next']['href']));

        return true;
    }

    public static function getResponseTimeStat($contact_id)
    {
        $kommo = new KommoCrmAPI(Yii::$app->params['kommo_api_config']['leads_stat']);

        $page = 1;
        $first_message_time = 0;
        $message_id = null;
        do {
            $response = $kommo->request('get', '/api/v4/events', [
                'page' => $page,
                'filter'=>[
                    'entity' => 'contact',
                    'entity_id' => $contact_id,
                    'created_by' => [ 9784763, 9779319, 9784755 ], // Karin, Fomov, Удаленный сотрудник
                    'type' => 'outgoing_chat_message', // Исходящее сообщение
                ]
            ]);
           
            if(empty($response['_links']['next']['href'])){
                if(!empty($response['_embedded']['events'])){
                    $first_message = array_pop($response['_embedded']['events']);
                    $first_message_time = $first_message['created_at'];
                    if(isset($first_message['value_after'][0]['message']['id'])) 
                        $message_id = $first_message['value_after'][0]['message']['id'];
                }
                break;
            }
            $page++;
        } while (!empty($response['_links']['next']['href']));

        return [
            $first_message_time,
            $message_id,
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $date = new \DateTime('@' . $this->created_at);
                $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));

                $hour = (int)$date->format('H'); // Получаем час

                if ($hour >= 20 || $hour < 9) {
                    $this->is_night = 1;

                    if ($hour >= 20) $date->modify('+1 day'); // Устанавливаем время на следующий день
                    
                    $date->setTime(9, 0); // Устанавливаем время на 9:00
                   
                    $this->created_at = $date->getTimestamp();
                }
            }

            if(empty($this->response_time_id) and !empty($this->response_time)){
                $time_ranges = KommoLeadsResponseTime::find()->all();
                foreach($time_ranges as $range){
                    if(empty($range->min) and !empty($range->max)){
                        if( $this->response_time < $range->max * 60 ) 
                            $this->response_time_id = $range->id;
                    }
                    if(!empty($range->min) and !empty($range->max)){
                        if( $this->response_time >= $range->min * 60 and $this->response_time < $range->max * 60 ) 
                            $this->response_time_id = $range->id;
                    }
                    if(!empty($range->min) and empty($range->max)){
                        if( $this->response_time > $range->min * 60 ) 
                            $this->response_time_id = $range->id;
                    }
                }
            }

            return true;
        }

        return false;
    }


     // всего верифицированных лидов
     public function isVerify()
     {
         return $this->status_id === self::STATUS_IDS['ПРОВЕРЕННЫЙ ЛИД']
                 or $this->status_id === self::STATUS_IDS['ПОДБОРКА ОТПРАВЛЕНА']
                 or $this->status_id === self::STATUS_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->status_id === self::STATUS_IDS['ВСТРЕЧА ПРОВЕДЕНА']
                 or $this->status_id === self::STATUS_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->status_id === self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
                 or $this->status_id === self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА']
                 or $this->rejection_id === self::REJECTION_IDS['ПРОВЕРЕННЫЙ ЛИД']
                 or $this->rejection_id === self::REJECTION_IDS['ПОДБОРКА ОТПРАВЛЕНА']
                 or $this->rejection_id === self::REJECTION_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->rejection_id === self::REJECTION_IDS['ВСТРЕЧА ПРОВЕДЕНА']
                 or $this->rejection_id === self::REJECTION_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->rejection_id === self::REJECTION_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО'];
     }
 
     // отправленных подборок
     public function isSentCollections()
     {
         return $this->status_id == self::STATUS_IDS['ПОДБОРКА ОТПРАВЛЕНА']
                 or $this->status_id == self::STATUS_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->status_id == self::STATUS_IDS['ВСТРЕЧА ПРОВЕДЕНА']
                 or $this->status_id == self::STATUS_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->status_id == self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
                 or $this->status_id == self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА']
                 or $this->rejection_id == self::REJECTION_IDS['ПОДБОРКА ОТПРАВЛЕНА']
                 or $this->rejection_id == self::REJECTION_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->rejection_id == self::REJECTION_IDS['ВСТРЕЧА ПРОВЕДЕНА']
                 or $this->rejection_id == self::REJECTION_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
                 or $this->rejection_id == self::REJECTION_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО'];
     }

    // выбрали заведение из нашей подборки
    public function isSelectedOurCollection()
    {
        return $this->status_id == self::STATUS_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ']
                or $this->status_id == self::STATUS_IDS['ВСТРЕЧА ПРОВЕДЕНА']
                or $this->status_id == self::STATUS_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
                or $this->status_id == self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
                or $this->status_id == self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА'];
    }

    // встреч с ресторанами
    public function isMeetingsVenues()
    {
        return $this->status_id == self::STATUS_IDS['ВСТРЕЧА ПРОВЕДЕНА']
               or $this->status_id == self::STATUS_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
               or $this->status_id == self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
               or $this->status_id == self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА'];
    }

    // арендовало помещение из нашей подборки
    public function isRentedRoom()
    {
        return $this->status_id == self::STATUS_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ']
               or $this->status_id == self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
               or $this->status_id == self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА'];
    }

    // мероприятий успешно проведено
    public function isTotalEvents()
    {
        return $this->status_id == self::STATUS_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО']
               or $this->status_id == self::STATUS_IDS['ОПЛАТА ПОЛУЧЕНА'];
    }

    // отвалилось на этапе верификации
    public function isRejectVerificationStage()
    {
        return $this->rejection_id === self::REJECTION_IDS['ПРОВЕРЕННЫЙ ЛИД'];
    }

    // отвалилось c этапа после отправки подборки
    public function isRejectRequestSentStage()
    {
        return $this->rejection_id === self::REJECTION_IDS['ПОДБОРКА ОТПРАВЛЕНА'];
    }

    // отвалилось с этапа выбрали заведение из нашей подборки
    public function isRejectSelectedOurCollectionStage()
    {
        return $this->rejection_id === self::REJECTION_IDS['КЛИЕНТ ВЫБРАЛ ЗАЛ ИЗ ПОДБОРКИ'];
    }

    // отвалилось с этапа встречи
    public function isRejectMeetingsVenuesStage()
    {
        return $this->rejection_id === self::REJECTION_IDS['ВСТРЕЧА ПРОВЕДЕНА'];
    }

    // отвалилось с этапа аренды помещения
    public function isRejectRentedRoom()
    {
        return $this->rejection_id === self::REJECTION_IDS['АРЕНДОВАЛ ЗАЛ ИЗ ПОДБОРКИ'];
    }

    // отвалилось с этапа успешно проведено
    public function isRejectEvents()
    {
        return $this->rejection_id === self::REJECTION_IDS['МЕРОПРИЯТИЕ ПРОВЕДЕНО'];
    }
 
}
