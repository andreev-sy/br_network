<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Restaurants;

/**
 * RestaurantsSearch represents the model behind the search form of `backend\models\Restaurants`.
 */
class RestaurantsSearch extends Restaurants
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gorko_id', 'min_capacity', 'max_capacity', 'price'], 'integer'],
            [['name', 'address', 'cover_url'], 'safe'],
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
        $query = Restaurants::find();

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
            'min_capacity' => $this->min_capacity,
            'max_capacity' => $this->max_capacity,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'cover_url', $this->cover_url]);

        return $dataProvider;
    }
}
