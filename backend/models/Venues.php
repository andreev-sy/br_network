<?php

namespace backend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues".
 *
 * @property int $id
 * @property int|null $site_id Сайт источник
 * @property int $status_id Статус
 * @property int $agglomeration_id Агломерация
 * @property int $city_id Город
 * @property int|null $district_id Район
 * @property int|null $region_id Округ
 * @property string|null $address Адрес
 * @property string $name Название
 * @property float|null $price_day Стоимость аренды за день
 * @property float|null $price_person Стоимость аренды на человека
 * @property float|null $price_hour Стоимость аренды за час
 * @property string|null $price_day_ranges Стоимость аренды за день диапазон
 * @property string|null $work_time Время работы
 * @property int|null $min_capacity Минимальная вместимость
 * @property int|null $max_capacity Максимальная вместимость
 * @property string|null $phone Контактный телефон
 * @property string|null $phone2 Контактный телефон 2
 * @property string|null $phone_wa Телефон для WA
 * @property string|null $param_spec Тип мероприятия
 * @property string|null $description Описание
 * @property string|null $comment Комментарий (не отображается)
 * @property int|null $manager_user_id Менеджер
 * @property int|null $vendor_user_id Продавец
 * @property int $is_processed Обработан
 * @property int $is_contract_signed Договор подписан
 * @property int $is_phoned Созвонились
 * @property string|null $param_type Тип площадки
 * @property string|null $param_location Расположение
 * @property int $param_kitchen Есть своя кухня
 * @property string|null $param_kitchen_type Кухня
 * @property string|null $param_cuisine Описание кухни
 * @property string|null $param_advanced_payment Размер предоплаты
 * @property int $param_firework Возможность проведения фейерверка
 * @property int $param_firecrackers Разрешены петарды
 * @property int $param_parking_dedicated Наличие выделенной парковки
 * @property string|null $param_parking Размер парковки
 * @property string|null $param_outdoor_capacity Вместимость территории на улице
 * @property int $param_alcohol Алкоголь в наличии
 * @property int|null $param_own_alcohol Можно свой алкоголь
 * @property int|null $param_decor_policy Правила украшения
 * @property int $param_dj Наличие DJ
 * @property string|null $param_extra_services Сервисы за отдельную плату
 * @property int $param_bridal_suite Номер для новобрачных
 * @property string|null $param_payment Способы оплаты
 * @property int $param_can_order_food Есть свадебное меню
 * @property int $param_own_menu Можно сформировать собственное меню
 * @property string|null $param_specials Особенности
 * @property string|null $param_seating_arrangement Расстановка столов
 * @property string|null $param_parking_type Парковка
 * @property string|null $param_video Видеопрезентация
 * @property string|null $latitude Ширина
 * @property string|null $longitude Долгота
 * @property string|null $google_id Google ID
 * @property string|null $google_place_id Google Place ID
 * @property string|null $google_about О заведении с гугула
 * @property string|null $google_description Описание с гугла
 * @property string|null $google_rating Рейтинг в гугл
 * @property int|null $google_reviews Количество гугл отзывов
 * @property string|null $google_reviews_link Ссылка гугл на отзывы
 * @property string|null $google_location_link Ссылка гугл на расположение
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property CollectionVenueVia[] $collectionVenueVias
 * @property Images[] $images
 * @property Rooms[] $rooms
 * @property Agglomeration $agglomeration
 * @property Cities $city
 * @property District $district
 * @property User $managerUser
 * @property User $vendorUser
 * @property VenuesSites $site
 * @property VenuesStatus $status
 * @property VenuesOwnAlcohol $paramOwnAlcohol
 * @property VenuesDecorPolicy $paramDecorPolicy
 * @property VenuesExtraServicesVia[] $venuesExtraServicesVias
 * @property VenuesKitchenTypeVia[] $venuesKitchenTypeVias
 * @property VenuesLocationVia[] $venuesLocationVias
 * @property VenuesParkingTypeVia[] $venuesParkingTypeVias
 * @property VenuesPaymentVia[] $venuesPaymentVias
 * @property VenuesSeatingArrangementVia[] $venuesSeatingArrangementVias
 * @property VenuesSpecVia[] $venuesSpecVias
 * @property VenuesSpecialVia[] $venuesSpecialVias
 * @property VenuesTypeVia[] $venuesTypeVias
 * @property VenuesVisit[] $venuesVisits
 */
class Venues extends \yii\db\ActiveRecord
{

    public $venues_images;
    public $price_search;
    public $capacity_search;
    public $param_spec_search;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agglomeration_id', 'city_id', 'name', 'status_id'], 'required'],
            [['site_id', 'status_id', 'agglomeration_id', 'city_id', 'district_id', 'region_id', 'min_capacity', 'max_capacity', 'manager_user_id', 'vendor_user_id', 'is_processed', 'is_contract_signed', 'is_phoned', 'param_kitchen', 'param_firework', 'param_firecrackers', 'param_parking_dedicated', 'param_alcohol', 'param_own_alcohol', 'param_decor_policy', 'param_dj', 'param_bridal_suite', 'param_can_order_food', 'param_own_menu', 'google_reviews'], 'integer'],
            [['name', 'address', 'price_day_ranges', 'work_time', 'description', 'comment', 'param_cuisine', 'param_advanced_payment', 'param_parking', 'param_outdoor_capacity', 'param_video', 'latitude', 'longitude', 'google_id', 'google_place_id', 'google_about', 'google_description', 'google_reviews_link', 'google_location_link'], 'string'],
            [['param_spec', 'param_type', 'param_location', 'param_kitchen_type', 'param_extra_services', 'param_payment', 'param_specials', 'param_seating_arrangement', 'param_parking_type'], 'safe'],
            [['price_day', 'price_person', 'price_hour'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['phone', 'phone2', 'phone_wa'], 'string', 'max' => 40],
            [['google_rating'], 'string', 'max' => 5],
            [['agglomeration_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agglomeration::className(), 'targetAttribute' => ['agglomeration_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['region_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['manager_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['manager_user_id' => 'id']],
            [['vendor_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['vendor_user_id' => 'id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesSites::className(), 'targetAttribute' => ['site_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['param_own_alcohol'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesOwnAlcohol::className(), 'targetAttribute' => ['param_own_alcohol' => 'id']],
            [['param_decor_policy'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesDecorPolicy::className(), 'targetAttribute' => ['param_decor_policy' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'site_id' => Yii::t('app', 'Сайт источник'),
            'status_id' => Yii::t('app', 'Статус'),
            'agglomeration_id' => Yii::t('app', 'Агломерация'),
            'city_id' => Yii::t('app', 'Город'),
            'district_id' => Yii::t('app', 'Район'),
            'region_id' => Yii::t('app', 'Округ'),
            'address' => Yii::t('app', 'Адрес'),
            'name' => Yii::t('app', 'Название'),
            'price_day' => Yii::t('app', 'Стоимость аренды за день'),
            'price_person' => Yii::t('app', 'Стоимость аренды на человека'),
            'price_hour' => Yii::t('app', 'Стоимость аренды за час'),
            'price_day_ranges' => Yii::t('app', 'Стоимость аренды за день диапазон'),
            'prices' => Yii::t('app', 'Стоимость аренды'),
            'prices_day' => Yii::t('app', 'За день'),
            'prices_hour' => Yii::t('app', 'За час'),
            'prices_person' => Yii::t('app', 'На человека'),
            'flags' => Yii::t('app', 'Флаги'),
            'users' => Yii::t('app', 'Пользователи'),
            'capacity' => Yii::t('app', 'Вместимость'),
            'min_capacity' => Yii::t('app', 'Минимальная вместимость'),
            'max_capacity' => Yii::t('app', 'Максимальная вместимость'),
            'work_time' => Yii::t('app', 'Время работы'),
            'phone' => Yii::t('app', 'Контактный телефон'),
            'phone2' => Yii::t('app', 'Контактный телефон 2'),
            'phone_wa' => Yii::t('app', 'Телефон для WA'),
            'param_spec' => Yii::t('app', 'Тип мероприятия'),
            'param_spec_search' => Yii::t('app', 'Тип мероприятия'),
            'description' => Yii::t('app', 'Описание'),
            'comment' => Yii::t('app', 'Комментарий (не отображается)'),
            'manager_user_id' => Yii::t('app', 'Менеджер'),
            'vendor_user_id' => Yii::t('app', 'Продавец'),
            'is_processed' => Yii::t('app', 'Обработан'),
            'is_contract_signed' => Yii::t('app', 'Договор подписан'),
            'is_phoned' => Yii::t('app', 'Созвонились'),
            'param_type' => Yii::t('app', 'Тип площадки'),
            'param_location' => Yii::t('app', 'Расположение'),
            'param_kitchen' => Yii::t('app', 'Есть своя кухня'),
            'param_kitchen_type' => Yii::t('app', 'Кухня'),
            'param_cuisine' => Yii::t('app', 'Описание кухни'),
            'param_advanced_payment' => Yii::t('app', 'Размер предоплаты'),
            'param_firework' => Yii::t('app', 'Возможность проведения фейерверка'),
            'param_firecrackers' => Yii::t('app', 'Разрешены петарды'),
            'param_parking_dedicated' => Yii::t('app', 'Наличие выделенной парковки'),
            'param_parking' => Yii::t('app', 'Размер парковки'),
            'param_outdoor_capacity' => Yii::t('app', 'Вместимость территории на улице'),
            'param_alcohol' => Yii::t('app', 'Алкоголь в наличии'),
            'param_own_alcohol' => Yii::t('app', 'Можно свой алкоголь'),
            'param_decor_policy' => Yii::t('app', 'Правила украшения'),
            'param_dj' => Yii::t('app', 'Наличие DJ'),
            'param_extra_services' => Yii::t('app', 'Сервисы за отдельную плату'),
            'param_bridal_suite' => Yii::t('app', 'Номер для новобрачных'),
            'param_payment' => Yii::t('app', 'Способы оплаты'),
            'param_can_order_food' => Yii::t('app', 'Есть свадебное меню'),
            'param_own_menu' => Yii::t('app', 'Можно сформировать собственное меню'),
            'param_specials' => Yii::t('app', 'Особенности'),
            'param_seating_arrangement' => Yii::t('app', 'Расстановка столов'),
            'param_parking_type' => Yii::t('app', 'Парковка'),
            'param_video' => Yii::t('app', 'Видеопрезентация'),
            'latitude' => Yii::t('app', 'Ширина'),
            'longitude' => Yii::t('app', 'Долгота'),
            'google_id' => Yii::t('app', 'Google ID'),
            'google_place_id' => Yii::t('app', 'Google Place ID'),
            'google_about' => Yii::t('app', 'О заведении с гугула'),
            'google_description' => Yii::t('app', 'Описание с гугла'),
            'google_rating' => Yii::t('app', 'Рейтинг в гугл'),
            'google_reviews' => Yii::t('app', 'Количество гугл отзывов'),
            'google_reviews_link' => Yii::t('app', 'Ссылка гугл на отзывы'),
            'google_location_link' => Yii::t('app', 'Ссылка гугл на расположение'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[CollectionVenueVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionVenueVias()
    {
        return $this->hasMany(CollectionVenueVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[Rooms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Rooms::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[Agglomeration]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgglomeration()
    {
        return $this->hasOne(Agglomeration::className(), ['id' => 'agglomeration_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[District]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }
    

    /**
     * Gets query for [[ManagerUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManagerUser()
    {
        return $this->hasOne(User::className(), ['id' => 'manager_user_id']);
    }

    /**
     * Gets query for [[VendorUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendorUser()
    {
        return $this->hasOne(User::className(), ['id' => 'vendor_user_id']);
    }

    /**
     * Gets query for [[Site]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(VenuesSites::className(), ['id' => 'site_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(VenuesStatus::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[ParamOwnAlcohol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParamOwnAlcohol()
    {
        return $this->hasOne(VenuesOwnAlcohol::className(), ['id' => 'param_own_alcohol']);
    }

    /**
     * Gets query for [[ParamDecorPolicy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParamDecorPolicy()
    {
        return $this->hasOne(VenuesDecorPolicy::className(), ['id' => 'param_decor_policy']);
    }

    /**
     * Gets query for [[VenuesExtraServicesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesExtraServicesVias()
    {
        return $this->hasMany(VenuesExtraServicesVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesKitchenTypeVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesKitchenTypeVias()
    {
        return $this->hasMany(VenuesKitchenTypeVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesLocationVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesLocationVias()
    {
        return $this->hasMany(VenuesLocationVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesParkingTypeVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesParkingTypeVias()
    {
        return $this->hasMany(VenuesParkingTypeVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesPaymentVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesPaymentVias()
    {
        return $this->hasMany(VenuesPaymentVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesSeatingArrangementVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSeatingArrangementVias()
    {
        return $this->hasMany(VenuesSeatingArrangementVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesSpecVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpecVias()
    {
        return $this->hasMany(VenuesSpecVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesSpecialVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesSpecialVias()
    {
        return $this->hasMany(VenuesSpecialVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesTypeVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesTypeVias()
    {
        return $this->hasMany(VenuesTypeVia::className(), ['venue_id' => 'id']);
    }

    /**
     * Gets query for [[VenuesVisits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesVisits()
    {
        return $this->hasMany(VenuesVisit::className(), ['venue_id' => 'id']);
    }

    public function getPriceDayRangesList()
    {
        if(empty($this->price_day_ranges)) return null;

        return implode('<br>', array_map(function($item) {
            return $item['mincapacity']. ' : '. $item['maxcapacity']. ' — '. Yii::$app->formatter->asCurrency($item['price'], 'BRL');
        }, json_decode($this->price_day_ranges, true)));
    }

    public function getMap()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }

    public function getWorkingTimeText()
    {
        if(empty($this->work_time)) return null;
        
        $workingTimeText = [];
        $workingTime = json_decode($this->work_time, true);
        
        if(!empty($workingTime)){
            foreach ($workingTime as $day => $hours) {
                $workingTimeText[] = "$day: $hours";
            }
    
            return implode(', ', $workingTimeText);
        }else{
            return implode(', ', explode('|',$this->work_time));
        }
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        VenuesSpecVia::deleteAll(['venue_id' => $this->id]);
        VenuesTypeVia::deleteAll(['venue_id' => $this->id]);
        VenuesKitchenTypeVia::deleteAll(['venue_id' => $this->id]);
        VenuesExtraServicesVia::deleteAll(['venue_id' => $this->id]);
        VenuesPaymentVia::deleteAll(['venue_id' => $this->id]);
        VenuesSpecialVia::deleteAll(['venue_id' => $this->id]);
        VenuesSeatingArrangementVia::deleteAll(['venue_id' => $this->id]);
        VenuesParkingTypeVia::deleteAll(['venue_id' => $this->id]);
        CollectionVenueVia::deleteAll(['venue_id' => $this->id]);
        VenuesVisit::deleteAll(['venue_id' => $this->id]);
        // Rooms::deleteAll(['venue_id' => $this->id]);

        return true;
    }

    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $props_for_convert = [
                'param_spec', 'param_type', 'param_location', 'param_kitchen_type', 'param_extra_services', 
                'param_payment', 'param_specials', 'param_seating_arrangement', 'param_parking_type'
            ];
    
            foreach($props_for_convert as $prop){
                $this->$prop = is_array($this->$prop) ? implode(',', $this->$prop) : (string)$this->$prop;
            }
        }

        parent::afterValidate();
    }

    public function beforeSave($insert) 
    {
        $props_for_convert = [
            'param_spec', 'param_type', 'param_location', 'param_kitchen_type', 'param_extra_services', 
            'param_payment', 'param_specials', 'param_seating_arrangement', 'param_parking_type'
        ];

        foreach($props_for_convert as $prop){
            $this->$prop = is_array($this->$prop) ? implode(',', $this->$prop) : (string)$this->$prop;
        }
        
        $post = Yii::$app->request->post('Venues');
        if(isset($post['price_day_ranges_arr']))
            $this->price_day_ranges = json_encode($post['price_day_ranges_arr'], true);

        $this->phone = rtrim($this->phone, '_');
        $this->phone2 = rtrim($this->phone2, '_');
        $this->phone_wa = rtrim($this->phone_wa, '_');

        return parent::beforeSave($insert);
    }
 

    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);

        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_spec',                 VenuesSpecVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_type',                 VenuesTypeVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_location',             VenuesLocationVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_kitchen_type',         VenuesKitchenTypeVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_extra_services',       VenuesExtraServicesVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_payment',              VenuesPaymentVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_specials',             VenuesSpecialVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_seating_arrangement',  VenuesSeatingArrangementVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_parking_type',         VenuesParkingTypeVia::className() );
    }
}
