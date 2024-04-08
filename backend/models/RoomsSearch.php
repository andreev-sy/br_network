<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rooms;

/**
 * RoomsSearch represents the model behind the search form of `backend\models\Rooms`.
 */
class RoomsSearch extends Rooms
{

    public $similar_address;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'venue_id', 'min_capacity', 'max_capacity', 'param_payment_model', 'param_rent_only', 'param_bright_room', 'param_separate_entrance', 'param_air_conditioner', 'param_floor', 'param_total_floors', 'is_loft', 'loft_food_catering', 'loft_food_catering_order', 'loft_food_order', 'loft_food_can_cook', 'loft_alcohol_allow', 'loft_alcohol_order', 'loft_alcohol_self', 'loft_alcohol_fee'], 'integer'],
            [['param_min_price', 'param_minimum_rental_duration', 'price_day', 'price_person', 'price_hour', 'price_day_ranges', 'param_spec', 'param_area', 'param_ceiling_height', 'param_location', 'param_features', 'param_name_alt', 'param_description', 'param_zones', 'loft_entrance', 'loft_style', 'loft_color', 'loft_light', 'loft_interior', 'loft_equipment_furniture', 'loft_equipment_interior', 'loft_equipment1', 'loft_equipment2', 'loft_equipment_games', 'loft_equipment_3', 'loft_staff', 'created_at', 'updated_at'], 'safe'],
            [['price_search', 'capacity_search', 'param_spec_search', 'similar_address'], 'safe'],
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
        $query = Rooms::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rooms.id' => $this->id,
            'rooms.venue_id' => $this->venue_id,
            // 'rooms.min_capacity' => $this->min_capacity,
            // 'rooms.max_capacity' => $this->max_capacity,
            'rooms.param_payment_model' => $this->param_payment_model,
            'rooms.param_rent_only' => $this->param_rent_only,
            'rooms.param_bright_room' => $this->param_bright_room,
            'rooms.param_separate_entrance' => $this->param_separate_entrance,
            'rooms.param_air_conditioner' => $this->param_air_conditioner,
            'rooms.param_floor' => $this->param_floor,
            'rooms.param_total_floors' => $this->param_total_floors,
            'rooms.is_loft' => $this->is_loft,
            'rooms.loft_food_catering' => $this->loft_food_catering,
            'rooms.loft_food_catering_order' => $this->loft_food_catering_order,
            'rooms.loft_food_order' => $this->loft_food_order,
            'rooms.loft_food_can_cook' => $this->loft_food_can_cook,
            'rooms.loft_alcohol_allow' => $this->loft_alcohol_allow,
            'rooms.loft_alcohol_order' => $this->loft_alcohol_order,
            'rooms.loft_alcohol_self' => $this->loft_alcohol_self,
            'rooms.loft_alcohol_fee' => $this->loft_alcohol_fee,
            'rooms.created_at' => $this->created_at,
            'rooms.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'rooms.param_min_price', $this->param_min_price])
            ->andFilterWhere(['like', 'rooms.param_minimum_rental_duration', $this->param_minimum_rental_duration])
            // ->andFilterWhere(['like', 'rooms.price_day', $this->price_day])
            // ->andFilterWhere(['like', 'rooms.price_person', $this->price_person])
            // ->andFilterWhere(['like', 'rooms.price_hour', $this->price_hour])
            ->andFilterWhere(['like', 'rooms.price_day_ranges', $this->price_day_ranges])
            ->andFilterWhere(['like', 'rooms.param_spec', $this->param_spec])
            ->andFilterWhere(['like', 'rooms.param_area', $this->param_area])
            ->andFilterWhere(['like', 'rooms.param_ceiling_height', $this->param_ceiling_height])
            ->andFilterWhere(['like', 'rooms.param_location', $this->param_location])
            ->andFilterWhere(['like', 'rooms.param_features', $this->param_features])
            ->andFilterWhere(['like', 'rooms.param_name_alt', $this->param_name_alt])
            ->andFilterWhere(['like', 'rooms.param_description', $this->param_description])
            ->andFilterWhere(['like', 'rooms.param_zones', $this->param_zones])
            ->andFilterWhere(['like', 'rooms.loft_entrance', $this->loft_entrance])
            ->andFilterWhere(['like', 'rooms.loft_style', $this->loft_style])
            ->andFilterWhere(['like', 'rooms.loft_color', $this->loft_color])
            ->andFilterWhere(['like', 'rooms.loft_light', $this->loft_light])
            ->andFilterWhere(['like', 'rooms.loft_interior', $this->loft_interior])
            ->andFilterWhere(['like', 'rooms.loft_equipment_furniture', $this->loft_equipment_furniture])
            ->andFilterWhere(['like', 'rooms.loft_equipment_interior', $this->loft_equipment_interior])
            ->andFilterWhere(['like', 'rooms.loft_equipment1', $this->loft_equipment1])
            ->andFilterWhere(['like', 'rooms.loft_equipment2', $this->loft_equipment2])
            ->andFilterWhere(['like', 'rooms.loft_equipment_games', $this->loft_equipment_games])
            ->andFilterWhere(['like', 'rooms.loft_equipment_3', $this->loft_equipment_3])
            ->andFilterWhere(['like', 'rooms.loft_staff', $this->loft_staff]);

        if(!empty($this->price_search['value'])){
            if($this->price_search['type'] === 'range'){
                $range = explode('-', $this->price_search['value']);
                if (isset($range[0]) && isset($range[1])) {
                    $query->andFilterWhere(['>=', 'rooms.'.$this->price_search['field'], $range[0]])
                          ->andFilterWhere(['<=', 'rooms.'.$this->price_search['field'], $range[1]]);
                }
            }elseif($this->price_search['type'] === 'more_than'){
                $query->andFilterWhere(['>', 'rooms.'.$this->price_search['field'], $this->price_search['value']]);
            }elseif($this->price_search['type'] === 'less_than'){
                $query->andFilterWhere(['<', 'rooms.'.$this->price_search['field'], $this->price_search['value']]);
            }
        }
        
    
        if(!empty($this->capacity_search['value'])){
            if($this->capacity_search['type'] === 'exactly'){
                $query->andFilterWhere(['<=', 'rooms.min_capacity', $this->capacity_search['value']]);
                $query->andFilterWhere(['>=', 'rooms.max_capacity', $this->capacity_search['value']]);
            }elseif($this->capacity_search['type'] === 'range'){
                $range = explode('-', $this->capacity_search['value']);
                if (isset($range[0]) && isset($range[1])) {
                    $query->andFilterWhere(['<=', 'rooms.min_capacity', $range[0]]);
                    $query->andFilterWhere(['>=', 'rooms.max_capacity', $range[1]]);
                }
            }elseif($this->capacity_search['type'] === 'more_than'){
                $query->andFilterWhere(['>', 'rooms.max_capacity', $this->capacity_search['value']]);
            }elseif($this->capacity_search['type'] === 'less_than'){
                $query->andFilterWhere(['<', 'rooms.max_capacity', $this->capacity_search['value']]);
            }
        }

        if (!empty($this->param_spec_search)) {
            $query->innerJoinWith(['roomsVenuesSpecVias']);
            $query->andFilterWhere(['IN', 'rooms_venues_spec_via.venues_spec_id', $this->param_spec_search]);
        }

        if (!empty($this->similar_address)) {
            $repeatAddresses = Rooms::find()
                ->joinWith(['venue'])
                ->select('venues.address')
                ->groupBy('venues.address')
                ->having('count(*) > 2')
                ->column();

            $query->joinWith(['venue']);
            $query->andWhere(['not', ['venues.address' => null]]);
            $query->andWhere(['not', ['venues.address' => '']]);
            $query->andWhere(['in', 'venues.address', $repeatAddresses]);
            $query->orderBy(['venues.address' => SORT_ASC]);
        }

        return $dataProvider;
    }
}
