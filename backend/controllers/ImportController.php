<?php

namespace backend\controllers;

use backend\models\District;
use Yii;
use backend\models\Cities;
use backend\models\Venues;
use backend\models\Rooms;
use backend\models\FormRequest;
use backend\models\Images;
use backend\models\Collection;
use backend\models\CollectionDistrictVia;
use backend\models\CollectionRegionVia;
use backend\models\CollectionVenueVia;
use backend\models\CollectionGuest;
use backend\models\CollectionPricePerson;
use backend\models\CollectionContactType;
use yii\web\Controller;

/**
 * RoomsController implements the CRUD actions for Rooms model.
 */
class ImportController extends Controller
{

    public function actionIndex()
    {
        // Venues::deleteAll();
        
        die;
        $props = 'Banquet hall|Banquet hall, Childrens party buffet, Wedding buffet|Salão de festas';

       
        $result = [];

        if(strpos($props, 'Banquet hall') !== false) $result[] = 1;
        if(strpos($props, 'Restaurant') !== false) $result[] = 2;
        if(strpos($props, 'Bar') !== false) $result[] = 12;
        if(strpos($props, 'Albergue') !== false or strpos($props, 'Hotel') !== false) $result[] = 3;


        die;
        $restaurants = Yii::$app->db->createCommand(
            "SELECT `extra`, `specials`, `options` FROM `banket-brazil`.`restaurant_common`"
        )->queryAll();

        $props = [];
        foreach($restaurants as $rest){
            $implode = implode('|', [ $rest['extra'], $rest['specials'], $rest['options'] ]);
            $props = array_merge($props, explode('|', $implode));
        }
        $props = array_unique($props);
        echo implode('<br>', $props);
        die;

        $var = 'cuisines';
        $restaurants = Yii::$app->db->createCommand(
            "SELECT DISTINCT($var) as $var FROM `banket-brazil`.`restaurant_common`"
        )->queryAll();

        $items = [];
        foreach($restaurants as $rest){
            if(!empty($rest[$var])){
                $items = array_merge($items, explode('|', $rest[$var]));
            }
        }
        // echo count($items).'<br>';
        // $items = array_unique($items);
        // echo count($items).'<br>';
        // die();

        $items_unique = [];
        foreach($items as $item){
            if(!empty($items_unique[$item])) $items_unique[$item] = $items_unique[$item] + 1;
            else $items_unique[$item] = 1;
        }

        foreach($items_unique as $key=>$item){
            echo "$key ($item)<br>";
        }
       
        die();
    }

    // ЭТО ОК (лимитами по 5к)
    public function actionVenues()
    {
        ini_set('memory_limit', '1024M');

        $restaurants = Yii::$app->db->createCommand(
            "SELECT * FROM `banket-brazil`.`restaurant_common` order by `banket-brazil`.`restaurant_common`.`id` limit 5000 offset 15000"
        )->queryAll();

        $restaurants_spec = Yii::$app->db->createCommand("SELECT * FROM `banket-brazil`.`restaurant_spec_via`")->queryAll();

        $specs = [];
        foreach($restaurants_spec as $spec){
            $specs[$spec['restaurant_id']][] = $spec['spec_id'];
        }

        $cities = Cities::find()->indexBy('name')->asArray()->all();
        $districts = District::find()->with('districtRegionVias')->indexBy('id')->asArray()->all();

        foreach ($restaurants as $rest) {
            $rest_city = trim($rest['city']);

            if(empty($cities[$rest_city])){
                $city = new Cities();
                $city->name = $rest_city;
                $city->save();
                $cities[$rest_city]['id'] = $city->id;
            }

            $status_id = !empty($rest['status']) ? $rest['status'] : 2;
            if($rest['archive']) $status_id = 5;

            $model = new Venues();
            $model->id = $rest['article'];
            $model->site_id = $rest['site_id'];
            $model->status_id = $status_id;
            $model->agglomeration_id = 1;
            $model->city_id = $cities[$rest_city]['id'];
            $model->name = $rest['name'];
            $model->region_id = !empty($districts[$rest['district_id']]) ? $districts[$rest['district_id']]['districtRegionVias'][0]['region_id'] : null;
            $model->district_id = !empty($districts[$rest['district_id']]) ? $rest['district_id'] : null;
            $model->address = !empty($rest['address']) ? $rest['address'] : $rest['full_address'];
            $model->price_day = !empty($rest['price_day']) ? str_replace(',','.', $rest['price_day']) : null;
            $model->price_person = !empty($rest['price_person']) ? str_replace(',','.', $rest['price_person']) : null;
            $model->price_hour = !empty($rest['price_hour']) ? str_replace(',','.', $rest['price_hour']) : null;
            $model->price_day_ranges = $rest['price_day_ranges'];
            $model->work_time = !empty($rest['work_time']) ? $rest['work_time'] : null;
            $model->min_capacity = $rest['min_capacity'];
            $model->max_capacity = $rest['max_capacity'];
            $model->phone = rtrim($rest['phone'], '_');
            $model->phone2 = rtrim($rest['phone2'], '_');
            $model->phone_wa = rtrim($rest['phone_wa'], '_');
            $model->param_spec = !empty($specs[$rest['article']]) ? implode(',', $specs[$rest['article']]) : null;
            $model->description = $rest['comment'];
            $model->comment = $rest['comment_hide'];
            $model->manager_user_id = $rest['user_id'];
            $model->vendor_user_id = $rest['vendor'];
            $model->is_processed = $rest['processed'];
            $model->is_contract_signed = $rest['contract_signed'];
            $model->is_phoned = $rest['phoned'];
            $model->param_pool = $rest['pool'];
            $model->param_open_area = $rest['open_area'];
            $model->param_place_barbecue = $rest['place_barbecue'];
            $model->param_type = $this->getType($rest);
            //$model->param_location = $rest[''];
            $model->param_kitchen = (int)$this->getProp($rest, 'Своя кухня');
            $model->param_kitchen_type =$this->getKitchenType($rest);
            //$model->param_cuisine = $rest[''];
            //$model->param_advanced_payment = $rest[''];
            //$model->param_firework = $rest[''];
            //$model->param_firecrackers = $rest[''];
            //$model->param_parking_dedicated = $rest[''];
            //$model->param_parking = $rest[''];
            //$model->param_outdoor_capacity = $rest[''];
            //$model->param_alcohol = $rest[''];
            //$model->param_own_alcohol = $rest[''];
            //$model->param_decor_policy = $rest[''];
            $model->param_dj = (int)$this->getProp($rest, 'Диджей');
            $model->param_extra_services = $this->getParamExtra($rest);
            //$model->param_bridal_suite = $rest[''];
            //$model->param_payment = $rest[''];
            //$model->param_can_order_food = $rest[''];
            //$model->param_own_menu = $rest[''];
            $model->param_specials = $this->getParamSpecial($rest);
            //$model->param_seating_arrangement = $rest[''];
            //$model->param_parking_type = $rest[''];
            //$model->param_video = $rest[''];
            $model->latitude = $rest['latitude'];
            $model->longitude = $rest['longitude'];
            $model->google_id = $rest['google_id'];
            $model->google_place_id = $rest['place_id'];
            $model->google_about = $rest['about'];
            $model->google_description = $rest['description'];
            $model->google_rating = $rest['rating'];
            $model->google_reviews = $rest['reviews'];
            $model->google_reviews_link = $rest['reviews_link'];
            $model->google_location_link = $rest['location_link'];

            if (!$model->save()) {
                echo '<pre>';
                print_r($model->errors);
                die;
            }
        }

    }

     // ЭТО ОК (лимитами по 5к)
    public function actionRooms()
    {
        ini_set('memory_limit', '1024M');

        $restaurants = Yii::$app->db->createCommand(
            "SELECT * FROM `banket-brazil`.`restaurant_common` order by `banket-brazil`.`restaurant_common`.`id` limit 5000 offset 15000"
        )->queryAll();

        $specs = Venues::find()->select(['id','param_spec'])->indexBy('id')->orderBy(['id'=>SORT_ASC])->asArray()->all();

 
        foreach ($restaurants as $rest) {
            $model = new Rooms();
            $model->venue_id = $rest['article'];
            $model->price_day = !empty($rest['price_day']) ? str_replace(',','.', $rest['price_day']) : null;
            $model->price_person = !empty($rest['price_person']) ? str_replace(',','.', $rest['price_person']) : null;
            $model->price_hour = !empty($rest['price_hour']) ? str_replace(',','.', $rest['price_hour']) : null;
            $model->price_day_ranges = $rest['price_day_ranges'];
            $model->min_capacity = $rest['min_capacity'];
            $model->max_capacity = $rest['max_capacity'];
            $model->param_rent_only = $rest['rent_without_food'];
            $model->param_area = !empty($rest['area']) ? str_replace(',','.', $rest['area']) : null;
            $model->param_zones = $this->getParamZones($rest);
            $model->param_spec = !empty($specs[$rest['article']]['param_spec']) ? $specs[$rest['article']]['param_spec'] : null;
            // $model->param_payment_model = null;
            // $model->param_rent_only = null;
            // $model->param_bright_room = null;
            $model->param_separate_entrance = (int)$this->getProp($rest, 'Отдельный вход');
            $model->param_air_conditioner = (int)$this->getProp($rest, 'Кондиционер');
            // $model->param_location = null;
            $model->param_features = $this->getParamFeatures($rest);
            $model->param_name_alt = $this->getRoomName($rest);
            // $model->param_description = null;
            // $model->is_loft = null;
            // $model->loft_food_catering = null;
            // $model->loft_food_catering_order = null;
            // $model->loft_food_order = null;
            // $model->loft_food_can_cook = null;
            // $model->loft_alcohol_allow = null;
            // $model->loft_alcohol_order = null;
            // $model->loft_alcohol_self = null;
            // $model->loft_alcohol_fee = null;
            $model->loft_entrance = $this->getProp($rest, 'Отдельный вход') ? 2 : null;
            // $model->loft_style = null;
            // $model->loft_color = null;
            // $model->loft_light = null;
            // $model->loft_interior = null;
            // $model->loft_equipment_furniture = null;
            // $model->loft_equipment_interior = null;
            $model->loft_equipment1 = $this->getLoftEquipment1($rest);
            $model->loft_equipment2 = $this->getProp($rest, 'Кофемашина') ? 4 : null;
            // $model->loft_equipment_games = null;
            $model->loft_equipment_3 = $this->getLoftEquipment3($rest);
            $model->loft_staff = $this->getLoftStaff($rest);

            if (!$model->save()) {
                echo '<pre>';
                print_r($model->errors);
                die;
            }

        }
    }

    // ЭТО ОК
    public function actionForm()
    {
        ini_set('memory_limit', '1024M');

        $form = Yii::$app->db->createCommand(
            "SELECT * FROM `banket-brazil`.`form_request` order by `banket-brazil`.`form_request`.`id`"
        )->queryAll();

        foreach ($form as $f) {
            if(empty($f['text'])) continue;

            $model = new FormRequest();
            $model->id = $f['id'];
            $model->text = $f['text'];
            $model->text_ru = $f['text_ru'];
            $model->text_full = $f['text_full'];
            $model->date = $f['date'];
            $model->type = $f['type'];
            $model->utm = $f['utm'];

            if (!$model->save()) {
                echo '<pre>';
                print_r($model->errors);
                die;
            }
        }
    }

    // ЭТО ОК
    public function actionImages()
    {
        ini_set('memory_limit', '1024M');

        $rest_images = Yii::$app->db->createCommand(
            "SELECT * FROM `banket-brazil`.`restaurant_image` ORDER BY `banket-brazil`.`restaurant_image`.`restaurant_id` ASC, `banket-brazil`.`restaurant_image`.`sort` ASC"
        )->queryAll();

        $rooms_ids = Rooms::find()->select(['id', 'venue_id'])->asArray()->all();
        $rooms = [];
        foreach($rooms_ids as $id){
            $rooms[$id['venue_id']] = $id['id'];
        }
        
        $old_rest = 0;
        $sort = 0;
        foreach ($rest_images as $rest_image) {
            if(empty($rooms[$rest_image['restaurant_id']])) continue;

            if($rest_image['restaurant_id'] !== $old_rest)
                $sort = 1;

            $model = new Images();
            $model->venue_id = $rest_image['restaurant_id'] !== $old_rest ? $rest_image['restaurant_id'] : null;
            $model->room_id = $rooms[$rest_image['restaurant_id']];
            $model->realpath = $rest_image['img_d'];
            $model->subpath = $rest_image['path'];
            $model->webppath = $rest_image['webp'];
            $model->timestamp = filectime($rest_image['img_d']);
            $model->sort = $sort;

            if (!$model->save()) {
                echo '<pre>';
                print_r($model->errors);
                die;
            }

            $sort++;
            $old_rest = $rest_image['restaurant_id'];
        }
    }

    // разобраться почему навыходе подборок меньше
    public function actionCollection()
    {
        ini_set('memory_limit', '1024M');
        // $form = FormRequest::find()->indexBy('id')->all();

        $collections = Yii::$app->db->createCommand("SELECT * FROM `banket-brazil`.`collection` ORDER BY `banket-brazil`.`collection`.`id`")->queryAll();
        $collection_district_via = Yii::$app->db->createCommand("SELECT * FROM `banket-brazil`.`collection_district_via` ORDER BY `banket-brazil`.`collection_district_via`.`collection_id`")->queryAll();
        $collection_region_via = Yii::$app->db->createCommand("SELECT * FROM `banket-brazil`.`collection_region_via` ORDER BY `banket-brazil`.`collection_region_via`.`collection_id`")->queryAll();
        $collection_restaurant_via = Yii::$app->db->createCommand("SELECT * FROM `banket-brazil`.`collection_restaurant_via` ORDER BY `banket-brazil`.`collection_restaurant_via`.`collection_id`")->queryAll();
        $collection_guest = CollectionGuest::find()->indexBy('text')->all();
        $collection_price_person = CollectionPricePerson::find()->indexBy('text')->all();
        $collection_contact_type = CollectionContactType::find()->indexBy('text')->all();

       
        foreach ($collections as $col) {
            // if(empty($form[$col['form_request_id']])) continue;
            if($col['user_id'] == 2 or $col['user_id'] == 5){
                $col['user_id'] = 9;
            }

            $model = new Collection();
            $model->id = $col['id'];
            $model->name = $col['name'];
            $model->date = $col['date'];
            $model->phone = $col['phone'];
            $model->spec_id = $col['event_id'];
            $model->guest_id = !empty($collection_guest[$col['guest']]) ? $collection_guest[$col['guest']]['id'] : null;
            $model->price_person_id = !empty($collection_price_person[$col['price_person']]) ? $collection_price_person[$col['price_person']]['id'] : null;
            $model->contact_type_id = !empty($collection_contact_type[$col['contact_type']]) ? $collection_contact_type[$col['contact_type']]['id'] : null;
            $model->agglomeration_id = 1;
            $model->city_id = 1;
            $model->desire = $col['desire'];
            $model->form_request_id = $col['form_request_id'];
            $model->manager_user_id = $col['user_id'];
            $model->pool = $col['pool'];
            $model->place_barbecue = $col['place_barbecue'];
            $model->open_area = $col['open_area'];
            $model->hash = $col['hash'];
            $model->created_at = $col['created_at'];
            $model->updated_at = $col['updated_at'];

            if (!$model->save()) {
                echo '<pre>';
                print_r($model->id);
                print_r($model->errors);
                die;
            }
        }

        foreach ($collection_district_via as $col) {
            $model = new CollectionDistrictVia();
            $model->collection_id = $col['collection_id'];
            $model->district_id = $col['district_id'];
            $model->save();
        }

        foreach ($collection_region_via as $col) {
            $model = new CollectionRegionVia();
            $model->collection_id = $col['collection_id'];
            $model->region_id = $col['region_id'];
            $model->save();
        }

        foreach ($collection_restaurant_via as $col) {
            $model = new CollectionVenueVia();
            $model->collection_id = $col['collection_id'];
            $model->venue_id = $col['restaurant_id'];
            $model->sort = $col['sort'];
            $model->active = $col['active'];
            $model->save();
        }
    }

    public function getProp($rest, $prop)
    {
        $extra = explode('|', $rest['extra']);
        $specials = explode('|', $rest['specials']);
        $option = explode('|', $rest['options']);

        $props = array_merge($extra, $specials, $option);

        if($prop === 'Диджей') return in_array('DJ', $props);
        if($prop === 'Кондиционер') return in_array('Ar Condicionado', $props);
        if($prop === 'Бесплатная Парковка') return in_array('Estacionamento Gratuito', $props);
        if($prop === 'Открытая площадка') return in_array('Área aberta ao ar livre', $props);
        if($prop === 'Шведский стол') return in_array('Serviço de Alimentos - Buffet', $props);
        if($prop === 'Отдельный вход') return in_array('Entrada Independente', $props);
        if($prop === 'Своя кухня') return in_array('Cozinha Equipada', $props);
        if($prop === 'Пассажирский лифт') return in_array('Elevadores', $props);
        if($prop === 'Грузовой лифт') return in_array('Elevadores de Carga', $props);
        if($prop === 'Сцена') return in_array('Palco', $props);
        if($prop === 'Живая музыка') return in_array('Música ao Vivo', $props);
        if($prop === 'Wi-Fi') return in_array('WiFi Gratuito', $props);
        if($prop === 'Проектор') return in_array('Projetor', $props);
        if($prop === 'TV LCD') return in_array('TV LCD', $props);
        if($prop === 'Звуковое оборудование') return in_array('Sonorização Básica', $props);
        if($prop === 'Условия для инвалидов') return (in_array('Banheiro para Deficiente', $props) or in_array('Acessibilidade', $props));
        if($prop === 'Специализированное освещение') return (in_array('Iluminação de Palco', $props) or in_array('Iluminação de Pista', $props) or in_array('Iluminação Cenográfica', $props) or in_array('Iluminação Decorativa', $props) or in_array('Iluminação Básica', $props));
        if($prop === 'Барная зона') return in_array('Bar', $props);
        if($prop === 'Ванная комната') return in_array('Banheiros', $props);
        if($prop === 'Зона для курения') return in_array('Área de Fumante', $props);
        if($prop === 'Танцпол') return in_array('Pista de Dança', $props);
        if($prop === 'Терраса') return in_array('Terraço', $props);
        if($prop === 'Маркерная доска/флипчарт') return in_array('Flipchat', $props);
        if($prop === 'Микрофон') return (in_array('Microfone Headset', $props) or in_array('Microfone Lapela', $props) or in_array('Microfone Sem Fio', $props));
        if($prop === 'Ноутбук') return in_array('Notebook', $props);
        if($prop === 'Экран для проектора') return in_array('Tela de Projeção', $props);
        if($prop === 'Blu-ray/DVD-плеер') return in_array('DVD Player', $props);
        if($prop === 'Кофемашина') return in_array('Máquina de Café', $props);
        if($prop === 'Бармен') return in_array('Barmen', $props);
        if($prop === 'Звукорежиссер') return in_array('Técnico de Som', $props);
        if($prop === 'Официант') return in_array('Garçons', $props);
        if($prop === 'Сад') return in_array('Jardim interno', $props);
        
        
        
        return null;
    }

    public function getParamExtra($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Диджей')) $result[] = 4;
        if($this->getProp($rest, 'Живая музыка')) $result[] = 6;
        if($this->getProp($rest, 'Шведский стол')) $result[] = 11;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getParamSpecial($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Wi-Fi')) $result[] = 6;
        if($this->getProp($rest, 'Проектор')) $result[] = 8;
        if($this->getProp($rest, 'Сцена')) $result[] = 7;
        if($this->getProp($rest, 'TV LCD')) $result[] = 9;
        if($this->getProp($rest, 'Звуковое оборудование')) $result[] = 4;
        if($this->getProp($rest, 'Сад')) $result[] = 14;
        if($this->getProp($rest, 'Условия для инвалидов')) $result[] = 11;
        if($this->getProp($rest, 'Пассажирский лифт') or $this->getProp($rest, 'Грузовой лифт')) $result[] = 17;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getType($rest)
    {
        $type = explode('|', $rest['type']);
        $subtypes = explode('|', $rest['subtypes']);
        $category = explode('|', $rest['category']);

        $props = array_merge($type, $subtypes, $category);
        $props = array_unique($props);
        $props = implode('|', $props);

        if(empty($props)) return null;

        $result = [];

        if(strpos($props, 'Banquet hall', ) !== false) $result[] = 1;
        if(strpos($props, 'Restaurant') !== false) $result[] = 2;
        if(strpos($props, 'Bar') !== false) $result[] = 12;
        if(strpos($props, 'Albergue') !== false or strpos($props, 'Hotel') !== false) $result[] = 3;

        return !empty($result) ?  implode(',', $result) : null;
    }

    public function getKitchenType($rest)
    {
        $props = $rest['cuisines'];

        if(empty($props)) return null;

        $result = [];

        if(strpos($props, 'Tradicional, Regional') !== false) $result[] = 3;
        if(strpos($props, 'Internacional') !== false) $result[] = 5;
        if(strpos($props, 'De autor') !== false) $result[] = 9;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }


    public function getLoftStaff($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Бармен')) $result[] = 5;
        if($this->getProp($rest, 'Диджей')) $result[] = 10;
        if($this->getProp($rest, 'Звукорежиссер')) $result[] = 11;
        if($this->getProp($rest, 'Официант')) $result[] = 17;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getLoftEquipment3($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Своя кухня')) $result[] = 3;
        if($this->getProp($rest, 'Проектор')) $result[] = 6;
        if($this->getProp($rest, 'Специализированное освещение')) $result[] = 8;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getLoftEquipment1($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Кондиционер')) $result[] = 10;
        if($this->getProp($rest, 'Маркерная доска/флипчарт')) $result[] = 11;
        if($this->getProp($rest, 'Микрофон')) $result[] = 12;
        if($this->getProp($rest, 'Ноутбук')) $result[] = 13;
        if($this->getProp($rest, 'Проектор')) $result[] = 14;
        if($this->getProp($rest, 'TV LCD')) $result[] = 18;
        if($this->getProp($rest, 'Экран для проектора')) $result[] = 19;
        if($this->getProp($rest, 'Blu-ray/DVD-плеер')) $result[] = 20;
        if($this->getProp($rest, 'Wi-Fi')) $result[] = 21;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getParamZones($rest)
    {
        $result = [];

        if($rest['pool'] == 1) $result[] = 5;
        if($rest['place_barbecue'] == 1) $result[] = 20;
        if($rest['open_area'] == 1) $result[] = 35;

        if($this->getProp($rest, 'Барная зона')) $result[] = 4;
        if($this->getProp($rest, 'Ванная комната')) $result[] = 7;
        if($this->getProp($rest, 'Зона для курения')) $result[] = 15;
        if($this->getProp($rest, 'Сцена')) $result[] = 27;
        if($this->getProp($rest, 'Танцпол')) $result[] = 28;
        if($this->getProp($rest, 'Терраса')) $result[] = 29;
        if($this->getProp($rest, 'Открытая площадка')) $result[] = 35;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

    public function getParamFeatures($rest)
    {
        $result = [];

        if($this->getProp($rest, 'Грузовой лифт')) $result[] = 3;
        if($this->getProp($rest, 'Пассажирский лифт')) $result[] = 7;
        if($this->getProp($rest, 'Условия для инвалидов')) $result[] = 12;
        if($this->getProp($rest, 'Специализированное освещение')) $result[] = 11;

        $result = implode(',', $result);

        return !empty($result) ? $result : null;
    }

 

    public function getRoomName($rest)
    {
        if(empty($rest['min_capacity']) or empty($rest['max_capacity'])) return null;

        $capacity = $rest['min_capacity'].'-'.$rest['max_capacity'];
        return "Um salão para $capacity pessoas";
    }


    // public function getWorktime($str)
    // {
    //     if(empty($str)) return null;

    //     json_decode($str);
    //     return json_last_error() === JSON_ERROR_NONE ? $str : $this->convertTimeFormat($str);
    // }

    // public function convertTimeFormat($str)
    // {
    //     $convertedTime = [];
    //     $timeSlots = explode('|', $str);
    //     $dayMappings = [
    //         'Ter' => 'Tuesday',
    //         'Qua' => 'Wednesday',
    //         'Qui' => 'Thursday',
    //         'Sex' => 'Friday',
    //         'Sáb' => 'Saturday',
    //         'Dom' => 'Sunday'
    //     ];

    //     if (!empty($timeSlots)) {
    //         foreach ($timeSlots as $timeSlot) {
    //             $parts = explode(':', $timeSlot);
    //             $day = trim($parts[0]);
    //             $time = trim($parts[1]);
    //             $convertedTime[$dayMappings[$day]] = str_replace(['h', '00'], [' ', 'AM-PM'], $time);
    //         }
    //     }

    //     return json_encode($convertedTime, JSON_UNESCAPED_UNICODE);
    // }


}
