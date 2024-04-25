<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Venues;

/**
 * VenuesSearch represents the model behind the search form of `backend\models\Venues`.
 */
class VenuesSearch extends Venues
{

    public $cityOptions;
    public $regionOptions;
    public $districtOptions;
    public $paramSpecOptions;
    public $managerOptions;
    public $vendorOptions;
    public $is_empty;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site_id', 'status_id', 'city_id', 'district_id', 'region_id', 'min_capacity', 'max_capacity', 'manager_user_id', 'vendor_user_id', 'is_processed', 'is_contract_signed', 'is_phoned', 'param_kitchen', 'param_pool', 'param_open_area', 'param_place_barbecue', 'param_firework', 'param_firecrackers', 'param_parking_dedicated', 'param_alcohol', 'param_own_alcohol', 'param_decor_policy', 'param_dj', 'param_bridal_suite', 'param_can_order_food', 'param_own_menu', 'google_reviews'], 'integer'],
            [['name', 'address', 'price_day_ranges', 'work_time', 'phone', 'phone2', 'phone_wa', 'param_spec', 'description', 'comment', 'param_type', 'param_location', 'param_kitchen_type', 'param_cuisine', 'param_advanced_payment', 'param_parking', 'param_outdoor_capacity', 'param_extra_services', 'param_payment', 'param_specials', 'param_seating_arrangement', 'param_parking_type', 'param_video', 'latitude', 'longitude', 'google_id', 'google_place_id', 'google_about', 'google_description', 'google_rating', 'google_reviews_link', 'google_location_link', 'processed_at', 'created_at', 'updated_at'], 'safe'],
            [['price_day', 'price_person', 'price_hour'], 'number'],
            [['price_search', 'capacity_search', 'param_spec_search', 'is_empty', 'is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Venues::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if(!empty($this->is_active)){
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'processed_at' => SORT_DESC]]
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'venues.id' => $this->id,
            'venues.site_id' => $this->site_id,
            'venues.status_id' => $this->status_id,
            'venues.city_id' => $this->city_id,
            'venues.district_id' => $this->district_id,
            'venues.region_id' => $this->region_id,
            // 'venues.name' => $this->name,
            // 'min_capacity' => $this->min_capacity,
            // 'max_capacity' => $this->max_capacity,
            'venues.manager_user_id' => $this->manager_user_id,
            'venues.vendor_user_id' => $this->vendor_user_id,
            'venues.is_processed' => $this->is_processed,
            'venues.is_contract_signed' => $this->is_contract_signed,
            'venues.is_phoned' => $this->is_phoned,
            'venues.param_kitchen' => $this->param_kitchen,
            'venues.param_pool' => $this->param_pool,
            'venues.param_open_area' => $this->param_open_area,
            'venues.param_place_barbecue' => $this->param_place_barbecue,
            'venues.param_firework' => $this->param_firework,
            'venues.param_firecrackers' => $this->param_firecrackers,
            'venues.param_parking_dedicated' => $this->param_parking_dedicated,
            'venues.param_alcohol' => $this->param_alcohol,
            'venues.param_own_alcohol' => $this->param_own_alcohol,
            'venues.param_decor_policy' => $this->param_decor_policy,
            'venues.param_dj' => $this->param_dj,
            'venues.param_bridal_suite' => $this->param_bridal_suite,
            'venues.param_can_order_food' => $this->param_can_order_food,
            'venues.param_own_menu' => $this->param_own_menu,
            'venues.google_reviews' => $this->google_reviews,
            'venues.created_at' => $this->created_at,
            'venues.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'venues.address', $this->address])
            ->andFilterWhere(['like', 'venues.name', $this->name])
            // ->andFilterWhere(['like', 'price_day', $this->price_day])
            // ->andFilterWhere(['like', 'price_person', $this->price_person])
            // ->andFilterWhere(['like', 'price_hour', $this->price_hour])
            ->andFilterWhere(['like', 'venues.price_day_ranges', $this->price_day_ranges])
            ->andFilterWhere(['like', 'venues.work_time', $this->work_time])
            ->andFilterWhere(['like', 'venues.phone', $this->phone])
            ->andFilterWhere(['like', 'venues.phone2', $this->phone2])
            ->andFilterWhere(['like', 'venues.phone_wa', $this->phone_wa])
            ->andFilterWhere(['like', 'venues.param_spec', $this->param_spec])
            ->andFilterWhere(['like', 'venues.description', $this->description])
            ->andFilterWhere(['like', 'venues.comment', $this->comment])
            ->andFilterWhere(['like', 'venues.param_type', $this->param_type])
            ->andFilterWhere(['like', 'venues.param_location', $this->param_location])
            ->andFilterWhere(['like', 'venues.param_kitchen_type', $this->param_kitchen_type])
            ->andFilterWhere(['like', 'venues.param_cuisine', $this->param_cuisine])
            ->andFilterWhere(['like', 'venues.param_advanced_payment', $this->param_advanced_payment])
            ->andFilterWhere(['like', 'venues.param_parking', $this->param_parking])
            ->andFilterWhere(['like', 'venues.param_outdoor_capacity', $this->param_outdoor_capacity])
            ->andFilterWhere(['like', 'venues.param_extra_services', $this->param_extra_services])
            ->andFilterWhere(['like', 'venues.param_payment', $this->param_payment])
            ->andFilterWhere(['like', 'venues.param_specials', $this->param_specials])
            ->andFilterWhere(['like', 'venues.param_seating_arrangement', $this->param_seating_arrangement])
            ->andFilterWhere(['like', 'venues.param_parking_type', $this->param_parking_type])
            ->andFilterWhere(['like', 'venues.param_video', $this->param_video])
            ->andFilterWhere(['like', 'venues.latitude', $this->latitude])
            ->andFilterWhere(['like', 'venues.longitude', $this->longitude])
            ->andFilterWhere(['like', 'venues.google_id', $this->google_id])
            ->andFilterWhere(['like', 'venues.google_place_id', $this->google_place_id])
            ->andFilterWhere(['like', 'venues.google_about', $this->google_about])
            ->andFilterWhere(['like', 'venues.google_description', $this->google_description])
            ->andFilterWhere(['like', 'venues.google_rating', $this->google_rating])
            ->andFilterWhere(['like', 'venues.google_reviews_link', $this->google_reviews_link])
            ->andFilterWhere(['like', 'venues.google_location_link', $this->google_location_link]);

        if(!empty($this->is_active)){
            $query->andFilterWhere(['in', 'status_id', [1, 2]]);
        }

        if(!empty($this->price_search['value'])){
            if($this->price_search['type'] === 'range'){
                $range = explode('-', $this->price_search['value']);
                if (isset($range[0]) && isset($range[1])) {
                    $query->andFilterWhere(['>=', 'venues.'.$this->price_search['field'], $range[0]])
                          ->andFilterWhere(['<=', 'venues.'.$this->price_search['field'], $range[1]]);
                }
            }elseif($this->price_search['type'] === 'more_than'){
                $query->andFilterWhere(['>', 'venues.'.$this->price_search['field'], $this->price_search['value']]);
            }elseif($this->price_search['type'] === 'less_than'){
                $query->andFilterWhere(['<', 'venues.'.$this->price_search['field'], $this->price_search['value']]);
            }
        }
     

        if(!empty($this->capacity_search['value'])){
            if($this->capacity_search['type'] === 'exactly'){
                $query->andFilterWhere(['<=', 'venues.min_capacity', $this->capacity_search['value']]);
                $query->andFilterWhere(['>=', 'venues.max_capacity', $this->capacity_search['value']]);
            }elseif($this->capacity_search['type'] === 'range'){
                $range = explode('-', $this->capacity_search['value']);
                if (isset($range[0]) && isset($range[1])) {
                    $query->andFilterWhere(['<=', 'venues.min_capacity', $range[0]]);
                    $query->andFilterWhere(['>=', 'venues.max_capacity', $range[1]]);
                }
            }elseif($this->capacity_search['type'] === 'more_than'){
                $query->andFilterWhere(['>', 'venues.max_capacity', $this->capacity_search['value']]);
            }elseif($this->capacity_search['type'] === 'less_than'){
                $query->andFilterWhere(['<', 'venues.max_capacity', $this->capacity_search['value']]);
            }
        }

        if (!empty($this->param_spec_search)) {
            $query->innerJoinWith(['venuesSpecVias']);
            $query->andFilterWhere(['IN', 'venues_spec_via.venues_spec_id', $this->param_spec_search]);
        }

        if (!empty($this->is_empty)) {
            $query->leftJoin('rooms', 'venues.id = rooms.venue_id')
                  ->andWhere(['rooms.venue_id' => null]);
        }

        // $query->joinWith(['site']);
        $query->joinWith(['status']);
        $query->joinWith(['city']);
        $query->joinWith(['region']);
        $query->joinWith(['district']);
        // $query->joinWith(['managerUser']);
        // $query->joinWith(['vendorUser']);
        // $query->joinWith(['paramOwnAlcohol']);
        // $query->joinWith(['paramDecorPolicy']);
        // $query->joinWith(['venuesExtraServicesVias.venuesExtraServices']);
        // $query->joinWith(['venuesKitchenTypeVias.venuesKitchenType']);
        // $query->joinWith(['venuesLocationVias.venuesLocation']);
        // $query->joinWith(['venuesParkingTypeVias.venuesParkingType']);
        // $query->joinWith(['venuesPaymentVias.venuesPayment']);
        // $query->joinWith(['venuesSeatingArrangementVias.venuesSeatingArrangement']);
        // $query->joinWith(['venuesSpecVias.venuesSpec']);
        // $query->joinWith(['venuesSpecialVias.venuesSpecial']);
        // $query->joinWith(['venuesTypeVias.venuesType']);


        // $this->cityOptions = $this->getCityOptions(clone $query);
        // $this->regionOptions = $this->getRegionOptions(clone $query);
        // $this->districtOptions = $this->getDistrictOptions(clone $query);
        // $this->managerOptions = $this->getManagerOptions(clone $query);
        // $this->vendorOptions = $this->getVendorOptions(clone $query);
        // $this->paramSpecOptions = $this->getParamSpecOptions(clone $query);

        return $dataProvider;
    }

    public function getCityOptions($query)
    {
        $ids = $query->select('venues.city_id')->distinct()->column();
        return Cities::find()->select('name')->where(['id' => $ids])->indexBy('id')->column();
    }

    public function getRegionOptions($query)
    {
        $ids = $query->select('venues.region_id')->distinct()->column();
        return Region::find()->select('name')->where(['id' => $ids])->indexBy('id')->column();
    }

    public function getDistrictOptions($query)
    {
        $ids = $query->select('venues.district_id')->distinct()->column();
        return District::find()->select('name')->where(['id' => $ids])->indexBy('id')->column();
    }

    public function getManagerOptions($query)
    {
        $ids = $query->select('venues.manager_user_id')->distinct()->column();
        return User::find()->select('fullname')->where(['id' => $ids])->indexBy('id')->column();
    }

    public function getVendorOptions($query)
    {
        $ids = $query->select('venues.vendor_user_id')->distinct()->column();
        return User::find()->select('fullname')->where(['id' => $ids])->indexBy('id')->column();
    }

    public function getParamSpecOptions($query)
    {
        $ids = $query->select('venues_spec_via.venues_spec_id')->distinct()->column();
        $field = Yii::$app->params['ru'] ? 'text_ru' : 'text';

        return VenuesSpec::find()->select($field)->where(['id' => $ids])->indexBy('id')->column();
    }

}
