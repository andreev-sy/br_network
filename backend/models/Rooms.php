<?php

namespace backend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $venue_id Заведение
 * @property float|null $param_min_price Минимальная стоимость банкета
 * @property int|null $param_minimum_rental_duration Минимальная длительность аренды
 * @property float|null $price_day Стоимость аренды за день
 * @property float|null $price_person Стоимость аренды на человека
 * @property float|null $price_hour Стоимость аренды за час
 * @property string|null $price_day_ranges Стоимость аренды за день диапазон
 * @property int|null $min_capacity Минимальная вместимость
 * @property int|null $max_capacity Максимальная вместимость
 * @property string|null $param_spec Тип мероприятия
 * @property int $param_payment_model Схема оплаты
 * @property int $param_rent_only Возможна аренда без еды
 * @property int $param_bright_room Светлый зал
 * @property int $param_separate_entrance Отдельный вход
 * @property int $param_air_conditioner Кондиционер
 * @property string|null $param_area Площадь
 * @property string|null $param_ceiling_height Высота потолка
 * @property int|null $param_floor Этаж 
 * @property int|null $param_total_floors Всего этажей в здании
 * @property string|null $param_location Расположение
 * @property string|null $param_features Особенности помещения
 * @property string|null $param_name_alt Название пространства (альтернативное)
 * @property string|null $param_description Описание
 * @property string|null $param_zones Функциональные зоны
 * @property int $is_loft Лофт
 * @property int $loft_food_catering Кейтеринг от площадки
 * @property int $loft_food_catering_order Можно заказать сторонний кейтеринг
 * @property int $loft_food_order Можно принести с собой или заказать доставку
 * @property int $loft_food_can_cook Можно готовить самим
 * @property int $loft_alcohol_allow Разрешен алкоголь
 * @property int $loft_alcohol_order Алкоголь доступен под заказ
 * @property int $loft_alcohol_self Алкоголь можно принести с собой
 * @property int $loft_alcohol_fee Пробковый сбор
 * @property string|null $loft_entrance Входы и выходы
 * @property string|null $loft_style Стиль
 * @property string|null $loft_color Цвет
 * @property string|null $loft_light Освещение
 * @property string|null $loft_interior Особенности интерьера
 * @property string|null $loft_equipment_furniture Мебель
 * @property string|null $loft_equipment_interior Предметы интерьера
 * @property string|null $loft_equipment1 Техника и другое оборудование
 * @property string|null $loft_equipment2 Принадлежности для еды и напитков
 * @property string|null $loft_equipment_games Игры
 * @property string|null $loft_equipment_3 Профессиональное оборудование
 * @property string|null $loft_staff Персонал
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property Images[] $images
 * @property Venues $venue
 * @property RoomsPaymentModel $paramPaymentModel
 * @property RoomsFeaturesVia[] $roomsFeaturesVias
 * @property RoomsLocationVia[] $roomsLocationVias
 * @property RoomsLoftColorVia[] $roomsLoftColorVias
 * @property RoomsLoftEntranceVia[] $roomsLoftEntranceVias
 * @property RoomsLoftEquipment1Via[] $roomsLoftEquipment1Vias
 * @property RoomsLoftEquipment2Via[] $roomsLoftEquipment2Vias
 * @property RoomsLoftEquipment3Via[] $roomsLoftEquipment3Vias
 * @property RoomsLoftEquipmentFurnitureVia[] $roomsLoftEquipmentFurnitureVias
 * @property RoomsLoftEquipmentGamesVia[] $roomsLoftEquipmentGamesVias
 * @property RoomsLoftEquipmentInteriorVia[] $roomsLoftEquipmentInteriorVias
 * @property RoomsLoftInteriorVia[] $roomsLoftInteriorVias
 * @property RoomsLoftLightVia[] $roomsLoftLightVias
 * @property RoomsLoftStaffVia[] $roomsLoftStaffVias
 * @property RoomsLoftStyleVia[] $roomsLoftStyleVias
 * @property RoomsVenuesSpecVia[] $roomsVenuesSpecVias
 * @property RoomsZonesVia[] $roomsZonesVias
 */
class Rooms extends \yii\db\ActiveRecord
{
    public $rooms_images;

    public $price_search;
    public $capacity_search;
    public $param_spec_search;

    
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
            [['venue_id'], 'required'],
            [['param_minimum_rental_duration','venue_id', 'min_capacity', 'max_capacity', 'param_payment_model', 'param_rent_only', 'param_bright_room', 'param_separate_entrance', 'param_air_conditioner', 'param_floor', 'param_total_floors', 'is_loft', 'loft_food_catering', 'loft_food_catering_order', 'loft_food_order', 'loft_food_can_cook', 'loft_alcohol_allow', 'loft_alcohol_order', 'loft_alcohol_self', 'loft_alcohol_fee'], 'integer'],
            [['price_day_ranges', 'param_area', 'param_ceiling_height', 'param_name_alt', 'param_description'], 'string'],
            [['param_spec', 'param_location', 'param_features', 'param_zones'], 'safe'],
            [['param_min_price', 'price_day', 'price_person', 'price_hour'], 'number'],
            [['loft_entrance', 'loft_style', 'loft_color', 'loft_light', 'loft_interior', 'loft_equipment_furniture', 'loft_equipment_interior', 'loft_equipment1', 'loft_equipment2', 'loft_equipment_games', 'loft_equipment_3', 'loft_staff'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['venue_id'], 'exist', 'message' => Yii::t('app', 'Заведение с таким ID не найден'), 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
            [['param_payment_model'], 'exist', 'skipOnError' => true, 'targetClass' => RoomsPaymentModel::className(), 'targetAttribute' => ['param_payment_model' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'venue_id' => Yii::t('app', 'Заведение'),
            'param_min_price' => Yii::t('app', 'Минимальная стоимость банкета'),
            'param_minimum_rental_duration' => Yii::t('app', 'Минимальная длительность аренды'),
            'price_day' => Yii::t('app', 'Стоимость аренды за день'),
            'price_person' => Yii::t('app', 'Стоимость аренды на человека'),
            'price_hour' => Yii::t('app', 'Стоимость аренды за час'),
            'price_day_ranges' => Yii::t('app', 'Стоимость аренды за день диапазон'),
            'prices' => Yii::t('app', 'Стоимость аренды'),
            'prices_day' => Yii::t('app', 'За день'),
            'prices_hour' => Yii::t('app', 'За час'),
            'prices_person' => Yii::t('app', 'На человека'),
            'capacity' => Yii::t('app', 'Вместимость'),
            'min_capacity' => Yii::t('app', 'Минимальная вместимость'),
            'max_capacity' => Yii::t('app', 'Максимальная вместимость'),
            'param_spec' => Yii::t('app', 'Тип мероприятия'),
            'param_spec_search' => Yii::t('app', 'Тип мероприятия'),
            'param_payment_model' => Yii::t('app', 'Схема оплаты'),
            'param_rent_only' => Yii::t('app', 'Возможна аренда без еды'),
            'param_bright_room' => Yii::t('app', 'Светлый зал'),
            'param_separate_entrance' => Yii::t('app', 'Отдельный вход'),
            'param_air_conditioner' => Yii::t('app', 'Кондиционер'),
            'param_area' => Yii::t('app', 'Площадь'),
            'param_ceiling_height' => Yii::t('app', 'Высота потолка'),
            'param_floor' => Yii::t('app', 'Этаж'),
            'param_total_floors' => Yii::t('app', 'Всего этажей в здании'),
            'param_location' => Yii::t('app', 'Расположение'),
            'param_features' => Yii::t('app', 'Особенности помещения'),
            'param_name_alt' => Yii::t('app', 'Название пространства (альтернативное)'),
            'param_description' => Yii::t('app', 'Описание'),
            'param_zones' => Yii::t('app', 'Функциональные зоны'),
            'is_loft' => Yii::t('app', 'Лофт'),
            'loft_food_catering' => Yii::t('app', 'Кейтеринг от площадки'),
            'loft_food_catering_order' => Yii::t('app', 'Можно заказать сторонний кейтеринг'),
            'loft_food_order' => Yii::t('app', 'Можно принести с собой или заказать доставку'),
            'loft_food_can_cook' => Yii::t('app', 'Можно готовить самим'),
            'loft_alcohol_allow' => Yii::t('app', 'Разрешен алкоголь'),
            'loft_alcohol_order' => Yii::t('app', 'Алкоголь доступен под заказ'),
            'loft_alcohol_self' => Yii::t('app', 'Алкоголь можно принести с собой'),
            'loft_alcohol_fee' => Yii::t('app', 'Пробковый сбор'),
            'loft_entrance' => Yii::t('app', 'Входы и выходы'),
            'loft_style' => Yii::t('app', 'Стиль'),
            'loft_color' => Yii::t('app', 'Цвет'),
            'loft_light' => Yii::t('app', 'Освещение'),
            'loft_interior' => Yii::t('app', 'Особенности интерьера'),
            'loft_equipment_furniture' => Yii::t('app', 'Мебель'),
            'loft_equipment_interior' => Yii::t('app', 'Предметы интерьера'),
            'loft_equipment1' => Yii::t('app', 'Техника и другое оборудование'),
            'loft_equipment2' => Yii::t('app', 'Принадлежности для еды и напитков'),
            'loft_equipment_games' => Yii::t('app', 'Игры'),
            'loft_equipment_3' => Yii::t('app', 'Профессиональное оборудование'),
            'loft_staff' => Yii::t('app', 'Персонал'),
            'rooms_images' => Yii::t('app', 'Изображения'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[Venue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }

    /**
     * Gets query for [[ParamPaymentModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParamPaymentModel()
    {
        return $this->hasOne(RoomsPaymentModel::className(), ['id' => 'param_payment_model']);
    }

    /**
     * Gets query for [[RoomsFeaturesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsFeaturesVias()
    {
        return $this->hasMany(RoomsFeaturesVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLocationVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLocationVias()
    {
        return $this->hasMany(RoomsLocationVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftColorVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftColorVias()
    {
        return $this->hasMany(RoomsLoftColorVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEntranceVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEntranceVias()
    {
        return $this->hasMany(RoomsLoftEntranceVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipment1Vias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipment1Vias()
    {
        return $this->hasMany(RoomsLoftEquipment1Via::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipment2Vias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipment2Vias()
    {
        return $this->hasMany(RoomsLoftEquipment2Via::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipment3Vias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipment3Vias()
    {
        return $this->hasMany(RoomsLoftEquipment3Via::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipmentFurnitureVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipmentFurnitureVias()
    {
        return $this->hasMany(RoomsLoftEquipmentFurnitureVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipmentGamesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipmentGamesVias()
    {
        return $this->hasMany(RoomsLoftEquipmentGamesVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftEquipmentInteriorVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftEquipmentInteriorVias()
    {
        return $this->hasMany(RoomsLoftEquipmentInteriorVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftInteriorVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftInteriorVias()
    {
        return $this->hasMany(RoomsLoftInteriorVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftLightVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftLightVias()
    {
        return $this->hasMany(RoomsLoftLightVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftStaffVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftStaffVias()
    {
        return $this->hasMany(RoomsLoftStaffVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsLoftStyleVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsLoftStyleVias()
    {
        return $this->hasMany(RoomsLoftStyleVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsVenuesSpecVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsVenuesSpecVias()
    {
        return $this->hasMany(RoomsVenuesSpecVia::className(), ['room_id' => 'id']);
    }

    /**
     * Gets query for [[RoomsZonesVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsZonesVias()
    {
        return $this->hasMany(RoomsZonesVia::className(), ['room_id' => 'id']);
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
        return ArrayHelper::map(self::find()->all(), 'id', function($data){
            return $data->param_name_alt ?? '#'.$data->id;
        });
    }

    public function uploadImages()
	{
		$newSortIndex = Images::find()->where(['room_id' => $this->id])->count();
        $newSortIndex++;
        
		foreach ($this->rooms_images as $file) {
            $dir = '/var/www/br_network/frontend/web/img_d/'.$this->venue_id.'/';
			FileHelper::createDirectory($dir);

            $filename = $this->venue_id.'_'.uniqid();
            $path = $dir . $filename.'.'.$file->extension;
            $webp = $dir . $filename.'.webp';

            if($file->saveAs($path)){
                exec("cwebp -m 6 $path -o $webp");
                $image = new Images();
                $image->room_id = $this->id;
                $image->realpath = $path;
                $image->subpath = str_replace('/var/www/br_network/frontend/web', '', $path);
                $image->webppath = str_replace('/var/www/br_network/frontend/web', '', $webp);
                $image->timestamp = time();
                $image->sort = $newSortIndex;
                $image->save();
                $newSortIndex++;
            }
		}
        
		return true;
	}


    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        RoomsVenuesSpecVia::deleteAll(['room_id' => $this->id]);
        RoomsLocationVia::deleteAll(['room_id' => $this->id]);
        RoomsFeaturesVia::deleteAll(['room_id' => $this->id]);
        RoomsZonesVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftEntranceVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftStyleVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftColorVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftLightVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftInteriorVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipmentFurnitureVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipmentInteriorVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipment1Via::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipment2Via::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipmentGamesVia::deleteAll(['room_id' => $this->id]);
        RoomsLoftEquipment3Via::deleteAll(['room_id' => $this->id]);
        RoomsLoftStaffVia::deleteAll(['room_id' => $this->id]);
        // Images::deleteAll(['room_id' => $this->id]);

        return true;
    }

    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $props_for_convert = [
                'param_spec', 'param_location', 'param_features', 'param_zones',
                'loft_entrance', 'loft_style', 'loft_color', 'loft_light', 'loft_interior', 'loft_equipment_furniture', 'loft_equipment_interior', 
                'loft_equipment1', 'loft_equipment2', 'loft_equipment_games', 'loft_equipment_3', 'loft_staff'
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
            'param_spec', 'param_location', 'param_features', 'param_zones',
            'loft_entrance', 'loft_style', 'loft_color', 'loft_light', 'loft_interior', 'loft_equipment_furniture', 'loft_equipment_interior', 
            'loft_equipment1', 'loft_equipment2', 'loft_equipment_games', 'loft_equipment_3', 'loft_staff'
        ];


        foreach($props_for_convert as $prop){
            $this->$prop = is_array($this->$prop) ? implode(',', $this->$prop) : (string)$this->$prop;
        }

        $post = Yii::$app->request->post('Rooms');
        if(isset($post['price_day_ranges_arr']))
            $this->price_day_ranges = json_encode($post['price_day_ranges_arr'], true);

        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);

      
        // loft_entrance
        // if (isset($changedAttributes['loft_entrance']) and $changedAttributes['loft_entrance'] !== $this->getAttribute('loft_entrance')) {
        //     RoomsLoftEntranceVia::deleteAll(['room_id' => $this->id]);
        //     $list_ids = explode(',', $this->getAttribute('loft_entrance'));
        //     if(!empty($list_ids)){
        //         foreach ($list_ids as $id) {
        //             $model = new RoomsLoftEntranceVia();
        //             $model->room_id = $this->getAttribute('id');
        //             $model->rooms_loft_entrance_id = $id;
        //             $model->save();
        //         }
        //     }
        // }

        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_spec',                 RoomsVenuesSpecVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_location',             RoomsLocationVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_features',             RoomsFeaturesVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'param_zones',                RoomsZonesVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_entrance',              RoomsLoftEntranceVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_style',                 RoomsLoftStyleVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_color',                 RoomsLoftColorVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_light',                 RoomsLoftLightVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_interior',              RoomsLoftInteriorVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment_furniture',   RoomsLoftEquipmentFurnitureVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment_interior',    RoomsLoftEquipmentInteriorVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment1',            RoomsLoftEquipment1Via::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment2',            RoomsLoftEquipment2Via::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment_games',       RoomsLoftEquipmentGamesVia::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_equipment_3',           RoomsLoftEquipment3Via::className() );
        ViaHelper::setRelatedLink( $insert, $changedAttributes, $this, 'loft_staff',                 RoomsLoftStaffVia::className() );


    }

 
}
