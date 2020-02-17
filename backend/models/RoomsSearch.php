<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rooms;

/**
 * RoomsSearch represents the model behind the search form of `backend\models\Rooms`.
 */
class RoomsSearch extends Rooms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gorko_id', 'restaurant_id', 'price', 'capacity_reception', 'capacity', 'type', 'rent_only', 'banquet_price', 'bright_room', 'separate_entrance'], 'integer'],
            [['name', 'type_name', 'features', 'cover_url'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gorko_id' => $this->gorko_id,
            'restaurant_id' => $this->restaurant_id,
            'price' => $this->price,
            'capacity_reception' => $this->capacity_reception,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'rent_only' => $this->rent_only,
            'banquet_price' => $this->banquet_price,
            'bright_room' => $this->bright_room,
            'separate_entrance' => $this->separate_entrance,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type_name', $this->type_name])
            ->andFilterWhere(['like', 'features', $this->features])
            ->andFilterWhere(['like', 'cover_url', $this->cover_url]);

        return $dataProvider;
    }
}
